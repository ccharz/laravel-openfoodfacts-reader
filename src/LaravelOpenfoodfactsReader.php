<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader;

use Ccharz\LaravelOpenfoodfactsReader\Contracts\Driver;
use Ccharz\LaravelOpenfoodfactsReader\Driver\ApiV2\Driver as ApiV2Driver;
use Ccharz\LaravelOpenfoodfactsReader\Driver\Local\Driver as LocalDriver;

class LaravelOpenfoodfactsReader
{
    const VERSION = '0.0.1';

    private array $readers = [];

    protected array $drivers = [
        'v2' => ApiV2Driver::class,
        'local' => LocalDriver::class,
    ];

    /**
     * Get a driver instance.
     *
     * @return \Ccharz\LaravelOpenfoodfactsReader\Contracts\Driver;
     */
    public function driver(?string $name = null): Driver
    {
        $name = $name ?: config('openfoodfactsreader.driver');

        if (isset($this->readers[$name])) {
            return $this->readers[$name];
        }

        $driver = isset($this->drivers[$name])
            ? $this->drivers[$name]
            : $name;

        return $this->readers[$name] = new $driver;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }
}
