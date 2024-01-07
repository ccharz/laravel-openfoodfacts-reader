<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

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

    protected function setUpDatabase()
    {
        $tableMigration = require __DIR__.'/../database/migrations/create_openfoodfacts_products_table.php.stub';

        $tableMigration->up();
    }

    protected function getTestDataPath(string $filename): string
    {
        return __DIR__.'/__data__/'.$filename;
    }

    protected function getTestData(string $filename): string
    {
        return file_get_contents($this->getTestDataPath($filename));
    }
}
