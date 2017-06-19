<?php

/**
 * This file is part of Moltin Cart, a PHP package to handle
 * your shopping basket.
 *
 * @package moltin/cart
 * @author Alan Wynn <hello@alanwynn.me>
 * @link http://github.com/moltin/laravel-cart
 *
 */

namespace Moltin\Cart\Storage;

use Moltin\Cart\Item;
use Moltin\Cart\Storage;
use Illuminate\Support\Facades\Config;

class LaravelFile implements \Moltin\Cart\StorageInterface
{
    protected $storagePath;

    protected $identifier;

    protected static $cart = array();

    public function __construct()
    {
        $folderName = Config::get('moltincart.storage_folder_name', 'Cart');
        $this->storagePath = storage_path($folderName);

        if (!file_exists($this->storagePath)) {
            mkdir($this->storagePath, 0777, true);
        }
    }

    /**
     * @param $identifier
     */
    public function restore($identifier)
    {
        $contents = null;
        $cartFilename = $this->storagePath . '/' . $identifier . '.json';

        if (file_exists($cartFilename)) {
            $contents = file_get_contents($cartFilename);
        }

        if (!empty($contents)) {
            $contents = json_decode($contents, true);
        }

        if (json_last_error() === JSON_ERROR_NONE) {
            static::$cart = $contents;
        }
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

        if ( ! $asArray) {
            return $cart;
        }

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
            if ($item->identifier == $identifier) {
                return true;
            }
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
            if ($item->identifier == $identifier) {
                return $item;
            }
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
            if ($item->id == $id) {
                return $item;
            }
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

        if (! array_key_exists($this->id, static::$cart)) {
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

    /**
     * Save the cart to persistant storage
     *
     * @return void
     */
    protected function saveCart()
    {
        $data = static::$cart;
        $cartFilename = $this->storagePath . '/' . $this->identifier . '.json';

        if (!empty($data)) {
            file_put_contents($cartFilename, json_encode($data));
        } else if (file_exists($cartFilename)) {
            unlink($cartFilename);
        }
    }
}
