<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader;

use Ccharz\LaravelOpenfoodfactsReader\Contracts\Driver;

class LaravelOpenfoodfactsReader
{
    const VERSION = '0.0.1';

    private array $readers = [];

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

        return $this->readers[$name] = new $name;
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
