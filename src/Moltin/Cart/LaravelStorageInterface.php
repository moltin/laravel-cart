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

namespace Moltin\Cart;

interface LaravelStorageInterface extends StorageInterface
{
    /**
     * This function is used to fire the event
     * into the Laravel system
     *
     * @return void
     */
    function fireEvent($event, $payload = array());
}
