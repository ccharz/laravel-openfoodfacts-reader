<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class LaravelOpenfoodfactsReaderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AboutCommand::add('Laravel Openfoodfacts Reader', fn () => ['Version' => LaravelOpenfoodfactsReader::VERSION]);

        $this->publishes([
            __DIR__.'/../config/openfoodfactsreader.php' => config_path('openfoodfactsreader.php'),
        ]);
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
