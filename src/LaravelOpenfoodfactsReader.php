<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader;

use Ccharz\LaravelOpenfoodfactsReader\Contracts\Driver;
use Ccharz\LaravelOpenfoodfactsReader\Driver\ApiV2\Driver as ApiV2Driver;
use Ccharz\LaravelOpenfoodfactsReader\Driver\Local\Driver as LocalDriver;
use Exception;

class LaravelOpenfoodfactsReader
{
    const VERSION = '0.0.1';

    /**
     * @var array<string,Driver>
     */
    private array $readers = [];

    /**
     * @var array<string,class-string<Driver>>
     */
    protected array $drivers = [
        'v2' => ApiV2Driver::class,
        'local' => LocalDriver::class,
    ];

    /**
     * Get a driver instance.
     */
    public function driver(?string $name = null): Driver
    {
        $name = $name ?? config('openfoodfactsreader.driver');

        if (is_string($name) && isset($this->readers[$name])) {
            return $this->readers[$name];
        }

        if (! is_string($name) || (! isset($this->drivers[$name]) && ! is_a($name, Driver::class, true))) {
            throw new Exception('Invalid driver');
        }

        /** @var class-string<Driver> $driver */
        $driver = $this->drivers[$name] ?? $name;

        return $this->readers[$name] = new $driver;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array<mixed>  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }
}
