<?php

namespace Moltin\Cart;

use Illuminate\Support\ServiceProvider;
use Moltin\Cart\Storage\Session as SessionStore;
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