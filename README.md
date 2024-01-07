# Laravel Openfoodfacts Reader

Read data from openfoodsfacts with a local driver and an api driver

## Installation

Install the package

First publish the configuration using

```
php artisan vendor:publish --tag openfoodfactsreader-config
```

and set the user agent. The User-Agent should be in the form of AppName/Version (ContactEmail). For example, MyApp/1.0 (contact@myapp.com).


## Drivers

### Local

This driver uses data imported from the official jsonl export from openfoodfacts. Keep in mind that the decompressed files has ~3.5 Million Products and take up to 50GB of storage at the moment.

#### Setup

First publish the migrations using

```
php artisan vendor:publish --tag openfoodfactsreader-migrations
```

Run `php artisan migrate` to migrate your database.

#### Import Database

1. Download the current database jsonl from https://static.openfoodfacts.org/data/openfoodfacts-products.jsonl.gz.
2. Decompress the downloaded file (for example using gunzip `gunzip openfoodfacts-products.jsonl.gz`)
3. Import the file into the database using the artisan command

```
php artisan openfoodfactsreader:import openfoodfacts-products.jsonl
```

(asserting the decompressed file exists in the root folder of your application).

#### Using your own model

```php
namespace App\Models;

use Ccharz\LaravelOpenfoodfactsReader\Driver\Local\OpenfoodfactsProduct as BaseOpenfoodfactsProduct;

class OpenfoodfactsProduct extends BaseOpenfoodfactsProduct
{
...
```

In the config file of the package you can specify your class:

```php
// config/openfoodfactsreader.php
...
   'model' => App\Models\OpenfoodfactsProduct::class
...
```


### API V2

This driver uses the official v2 api.

#### Setup

Publish the configuration using

```sh
php artisan vendor:publish --tag openfoodfactsreader-config
```

and set the user agent. The User-Agent should be in the form of AppName/Version (ContactEmail). For example, MyApp/1.0 (contact@myapp.com).

## Usage

### Get Product Data By Barcode

```php
/** @var Ccharz\LaravelOpenfoodfactsReader\Driver\Local\OpenfoodfactsProduct $product */
$product = app(LaravelOpenfoodfactsReader::class)->product('737628064502');
```

You can access the product data via the `data` method of the OpenfoodfactsProduct Model.

### Driver Selection

You can either use the default driver configured in the config file or specify it using the **driver** method.

```php
app(LaravelOpenfoodfactsReader::class)->driver('local')->product('737628064502');
app(LaravelOpenfoodfactsReader::class)->driver('v2')->product('737628064502');
```
