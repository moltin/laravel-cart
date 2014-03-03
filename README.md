Laravel Shopping Cart Package
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
// Format array of required info for item to be added to basket...
$items = array(
	'id' => 1,
	'name' => 'Juicy Picnic Hamper',
	'price' => 120.00,
	'quantity' => 1
);

// Make the insert...
Cart::insert($items);

// Let's see what we have have in there...
dd(Cart::totalItems());
```

###Config settings (Optional)
Add the file moltincart.php in the app/config folder
```php

return array(
    'storage' => 'session', //session, cache

    // Cache
    // Laravel documentation for more information
    'cache_prefix' => 'moltin_cart_',
    'cache_expire' => '-1', //in minutes, -1  permanent caching

    // Identifier
    // With a requestcookie (choose for storage: cache, the session will be expired), the cart could be reloaded from a http request, example: the customer could save his cart link (email, hyperlink) and reopen this later.
    // If there is no request, the cookie will be loaded.
    'identifier' => 'cookie', //cookie, requestcookie

    //Request Cookie
    'requestid' => 'CartID', //http input request identifier, example: your customer/backoffice could reload the cart in your shop controller, /public/shop?CartID=871f0bc18ca76e68bf7c3adf8f9426ef
);
```


### Setting the tax rate for an item
Another key you can pass to your insert method is 'tax'. This is a percentage which you would like to be added onto
the price of the item.

In the below example we will use 20% for the tax rate.

```php
Cart::insert(array(
    'id'       => 'foo',
    'name'     => 'bar',
    'price'    => 100,
    'quantity' => 1,
    'tax'      => 20
));
```

### Updating items in the cart
You can update items in your cart by updating any property on a cart item. For example, if you were within a
cart loop then you can update a specific item using the below example.
```php
foreach (Cart::contents() as $item) {
    $item->name = 'Foo';
    $item->quantity = 1;
}
```

### Removing cart items
You can remove any items in your cart by using the ```remove()``` method on any cart item.
```php
foreach (Cart::contents() as $item) {
    $item->remove();
}
```

### Destroying/emptying the cart
You can completely empty/destroy the cart by using the ```destroy()``` method.
```php
Cart::destroy()
```

### Retrieve the cart contents
You can loop the cart contents by using the following method
```php
Cart::contents();
```

You can also return the cart items as an array by passing true as the first argument
```php
Cart::contents(true);
```

### Retrieving the total items in the cart
```php
Cart::totalItems();
```

By default this method will return all items in the cart as well as their quantities. You can pass ```true```
as the first argument to get all unique items.
```php
Cart::totalItems(true);
```

### Retrieving the cart total
```php
$cart->total();
```

By default the ```total()``` method will return the total value of the cart as a ```float```, this will include
any item taxes. If you want to retrieve the cart total without tax then you can do so by passing false to the
```total()``` method
```php
Cart::total(false);
```

### Check if the cart has an item
```php
Cart::has($itemIdentifier);
```

### Retreive an item object by identifier
```php
Cart::item($itemIdentifier);
```

## Cart items
There are several features of the cart items that may also help when integrating your cart.

### Retrieving the total value of an item
You can retrieve the total value of a specific cart item (including quantities) using the following method.
```php
$item->total();
```

By default, this method will return the total value of the item plus tax. So if you had a product which costs 100,
with a quantity of 2 and a tax rate of 20% then the total returned by this method would be 240.

You can also get the total minus tax by passing false to the ```total()``` method.
```php
$item->total(false);
```

This would return 200.

### Check if an item has options
You can check if a cart item has options by using the ```hasOptions()``` method.

```php
if ($item->hasOptions()) {
    // We have options
}
```

### Remove an item from the cart
```php
$item->remove();
```

### Output the item data as an array
```php
$item->toArray();
```
