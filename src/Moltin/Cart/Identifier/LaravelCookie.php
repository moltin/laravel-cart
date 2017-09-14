<?php

/**
 * This file is part of Moltin Cart, a PHP package to handle
 * your shopping basket.
 *
 * @package moltin/laravel-cart
 * @author Alan Wynn <hello@alanwynn.me>
 * @link http://github.com/moltin/laravel-cart
 *
 */

namespace Moltin\Cart\Identifier;

use Illuminate\Support\Facades\Config;

class LaravelCookie extends Cookie
{
    /**
     * Regenerate the identifier
     *
     * @return string The identifier
     */
    public function regenerate()
    {
        $identifier = md5(uniqid(null, true));

        setcookie('cart_identifier', $identifier, 0, '/', Config::get("session.domain"));

        return $identifier;
    }

    /**
     * Forget the identifier
     *
     * @return void
     */
    public function forget()
    {
        return setcookie('cart_identifier', null, time()-3600, '/', Config::get("session.domain"));
    }
}
