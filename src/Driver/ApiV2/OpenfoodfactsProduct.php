<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Driver\ApiV2;

use Ccharz\LaravelOpenfoodfactsReader\Contracts\OpenfoodfactsProduct as OpenfoodfactsProductContract;
use Illuminate\Support\Arr;

class OpenfoodfactsProduct implements OpenfoodfactsProductContract
{
    public function __construct(protected readonly array $data)
    {
    }

    public function data(?string $key = null, mixed $default = null): mixed
    {
        if (! isset($key)) {
            return $this->data;
        }

        return Arr::get($this->data, $key, $default);
    }

    public function __get(string $key): mixed
    {
        return $this->data($key);
    }
}
