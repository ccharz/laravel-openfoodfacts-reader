<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Openfoodfacts Driver
    |--------------------------------------------------------------------------
    */

    'driver' => \Ccharz\LaravelOpenfoodfactsReader\Driver\ApiV2\Driver::class,

    /*
    |--------------------------------------------------------------------------
    | User Agent
    |--------------------------------------------------------------------------
    | We ask you to always use a custom User-Agent to identify you (to not risk
    | being identified as a bot). The User-Agent should be in the form of
    | AppName/Version (ContactEmail). For example, MyApp/1.0 (contact@myapp.com)
    |
    | https://openfoodfacts.github.io/openfoodfacts-server/api/#authentication
    */

    'user_agent' => null,

    /*
    |--------------------------------------------------------------------------
    | Storage Disk
    |--------------------------------------------------------------------------
    |
    | Disk on which the data of the local driver should be stored
    |
    */

    'disk' => null,

    /*
    |--------------------------------------------------------------------------
    | Storage Path
    |--------------------------------------------------------------------------
    |
    | Path in which the data of the local driver should be stored
    |
    */

    'path' => 'openfoodfacts',

    /*
    |--------------------------------------------------------------------------
    | Product Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model if using the local driver
    */

    'model' => \Ccharz\LaravelOpenfoodfactsReader\Driver\Local\OpenfoodfactsProduct::class,
];
