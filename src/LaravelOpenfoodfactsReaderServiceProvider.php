<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader;

use Ccharz\LaravelOpenfoodfactsReader\Console\ImportDatabase;
use Illuminate\Support\ServiceProvider;

class LaravelOpenfoodfactsReaderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/openfoodfactsreader.php' => config_path('openfoodfactsreader.php'),
        ], 'openfoodfactsreader-config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_openfoodfacts_products_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_openfoodfacts_products_table.php'),
        ], 'openfoodfactsreader-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportDatabase::class,
            ]);
        }
    }

    public function register()
    {
        $this->app->singleton(LaravelOpenfoodfactsReader::class, function () {
            return new LaravelOpenfoodfactsReader();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/openfoodfactsreader.php', 'openfoodfactsreader'
        );
    }
}
