<?php namespace Moltin\Cart\Storage;
/**
 * Class LaravelCache
 * @package Moltin\Cart\Storage
 * @author Theo den Hollander <theo@hollandware.com>
 */

use Moltin\Cart\Item;
use Moltin\Cart\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache as LaravelCacheStorage;

class LaravelCache implements \Moltin\Cart\StorageInterface
{
    private $cachePrefix;

    protected $identifier;

    protected static $cart = array();


    public function __construct()
    {
        $this->cachePrefix = Config::get('moltincart.cache_prefix', 'session');
    }

    /**
     * @param $identifier
     */
    public function restore($identifier)
    {
        $carts = LaravelCacheStorage::get($this->cachePrefix . $identifier);

        if ($carts) static::$cart = $carts;
    }

    /**
     * Add or update an item in the cart
     *
     * @param  Item   $item The item to insert or update
     * @return void
     */
    public function insertUpdate(Item $item)
    {
        static::$cart[$this->id][$item->identifier] = $item;

        $this->saveCart();
    }

    /**
     * Retrieve the cart data
     *
     * @param bool $asArray
     * @return array
     */
    public function &data($asArray = false)
    {
        $cart =& static::$cart[$this->id];

        if ( ! $asArray) return $cart;

        $data = $cart;

        foreach ($data as &$item) {
            $item = $item->toArray();
        }

        return $data;
    }

    /**
     * Check if the item exists in the cart
     *
     * @param $identifier
     * @internal param mixed $id
     * @return boolean
     */
    public function has($identifier)
    {
        foreach (static::$cart[$this->id] as $item) {

            if ($item->identifier == $identifier) return true;

        }

        return false;
    }

    /**
     * Get a single cart item by id
     *
     * @param  mixed $id The item id
     * @return Item  The item class
     */
    public function item($identifier)
    {
        foreach (static::$cart[$this->id] as $item) {

            if ($item->identifier == $identifier) return $item;

        }

        return false;
    }

    /**
     * Returns the first occurance of an item with a given id
     *
     * @param  string $id The item id
     * @return Item       Item object
     */
    public function find($id)
    {
        foreach (static::$cart[$this->id] as $item) {

            if ($item->id == $id) return $item;

        }

        return false;
    }

    /**
     * Remove an item from the cart
     *
     * @param  mixed $id
     * @return void
     */
    public function remove($id)
    {
        unset(static::$cart[$this->id][$id]);

        $this->saveCart();
    }

    /**
     * Destroy the cart
     *
     * @return void
     */
    public function destroy()
    {
        static::$cart[$this->id] = array();

        $this->saveCart();
    }

    /**
     * Set the cart identifier
     *
     * @param string $identifier
     */
    public function setIdentifier($id)
    {
        $this->id = $id;

        if ( ! array_key_exists($this->id, static::$cart)) {
            static::$cart[$this->id] = array();
        }

        $this->saveCart();
    }

    /**
     * Return the current cart identifier
     *
     * @return void
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    protected function saveCart()
    {
        $data = static::$cart;

        $expires = Config::get('moltincart.cache_expire', 60);
        $cacheID = $this->cachePrefix . $this->id;

        if($expires == -1)
        {
            LaravelCacheStorage::forever($cacheID, $data);
        }
        else
        {
            LaravelCacheStorage::put($cacheID, $data, $expires);
        }
    }
}
