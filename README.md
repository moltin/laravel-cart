laravel-cart
============

Laravel Facade and Service Provider for Moltin\Cart

Installation
---

To use, simply install the package via Composer and then add the following to your app/config/app.php to the service providers array:

```php
'Moltin\Cart\CartServiceProvider',
```

Then add to the aliases array the following:
```php
'Cart' => 'Moltin\Cart\Facade',
```

You should then be good to go and be able to access the cart using the following static interface:

```php
//Format array of required info for item to be added to basket...
$items = array(
	'id' => 1,
	'name' => 'Juicy Picnic Hamper',
	'price' => 120.00,
	'quantity' => 1
);

//Make the insert...
Cart::insert($items);

//Let's see what we have got in their...
dd(Cart::totalItems());
```
