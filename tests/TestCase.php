<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            \Ccharz\LaravelOpenfoodfactsReader\LaravelOpenfoodfactsReaderServiceProvider::class,
        ];
    }

    protected function getTestDataPath(string $filename): string
    {
        return __DIR__.'/__data__/'.$filename;
    }

    protected function getTestData(string $filename): string
    {
        return file_get_contents($this->getTestDataPath($filename));
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $tableMigration = require __DIR__.'/../database/migrations/create_openfoodfacts_products_table.php.stub';

        $tableMigration->up();

        $this->beforeApplicationDestroyed(
            fn () => $tableMigration->down()
        );
    }
}
