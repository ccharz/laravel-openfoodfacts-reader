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

    protected function getTestData(string $filename): string
    {
        return file_get_contents(__DIR__.'/__data__/'.$filename);
    }
}
