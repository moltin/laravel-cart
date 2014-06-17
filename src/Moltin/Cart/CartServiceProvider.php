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
use Illuminate\Support\Facades\Config;

use Moltin\Cart\Storage\LaravelSession as SessionStore;
use Moltin\Cart\Storage\LaravelCache as CacheStore;
use Moltin\Cart\Identifier\Cookie as CookieIdentifier;
use Moltin\Cart\Identifier\RequestCookie as CookieRequestIdentifier;

class CartServiceProvider extends ServiceProvider
{
    public function getStorageService()
    {
        $class = $this->app['config']->get('laravel-cart::moltincart.storage', 'session');
        switch ($class) {
            case 'session':
                return new SessionStore;
            break;

            case 'cache':
                return new CacheStore;
            break;

            default:
                return $this->app->make($class);
            break;
        }
    }

    public function getIdentifierService()
    {
        $class = $this->app['config']->get('laravel-cart::moltincart.identifier', 'cookie');
        switch ($class) {
            case 'requestcookie':
                return new CookieRequestIdentifier;
            break;

            case 'cookie':
                return new CookieIdentifier;
            break;

            default:
                return $this->app->make($class);
            break;
        }
    }

    public function register()
    {
        $that = $this;

        $this->app->singleton('cart', function() use ($that) {
            return new Cart($that->getStorageService(), $that->getIdentifierService());
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->package('moltin/laravel-cart');
    }
}
