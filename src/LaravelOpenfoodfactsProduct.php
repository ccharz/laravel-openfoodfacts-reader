<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader;

use Ccharz\LaravelOpenfoodfactsReader\Contracts\Product;
use Illuminate\Support\Arr;

abstract class LaravelOpenfoodfactsProduct implements Product
{
    protected array $data;

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function __isset(string $key): bool
    {
        return ! is_null(Arr::get($this->data, $key));
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->data, $key, $default);
    }

    public function __get(string $key): mixed
    {
        return $this->get($key);
    }
}
