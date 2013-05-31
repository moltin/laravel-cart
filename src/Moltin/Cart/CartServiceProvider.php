<?php

/**
* This file is part of Moltin Cart for Laravel 4, a PHP
* package to provide a Service Provider and Facade for
* the Moltin\Cart package.
*
* Copyright (c) 2013 Moltin Ltd.
* http://github.com/moltin/laravel-cart
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
* @package moltin/laravel-cart
* @author Chris Harvey <chris@molt.in>
* @copyright 2013 Moltin Ltd.
* @version dev
* @link http://github.com/moltin/laravel-cart
*
*/

namespace Moltin\Cart;

use Illuminate\Support\ServiceProvider;
use Moltin\Cart\Storage\LaravelSession as SessionStore;
use Moltin\Cart\Identifier\Cookie as CookieIdentifier;

class CartServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('cart', function() {
            return new Cart(new SessionStore, new CookieIdentifier);
        });
    }
}