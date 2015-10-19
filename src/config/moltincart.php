<?php

return array(
	'storage' => 'session', //session, cache, file

    // Cache
    // Laravel documentation for more information
    'cache_prefix' => 'moltin_cart_',
    'cache_expire' => '-1', //in minutes, -1  permanent caching

    // Filesystem
    // Folder Name inside the Storage Path
    'storage_folder_name' => 'moltin_cart',

    // Identifier
    // With a requestcookie (choose for storage: cache, the session will be expired), the cart could be reloaded from a http request, example: the customer could save his cart link (email, hyperlink) and reopen this later.
    // If there is no request, the cookie will be loaded.
    'identifier' => 'cookie', //cookie, requestcookie

    //Request Cookie
    'requestid' => 'CartID', //http input request identifier, example: your customer/backoffice could reload the cart in your shop controller, /public/shop?CartID=871f0bc18ca76e68bf7c3adf8f9426ef
);
