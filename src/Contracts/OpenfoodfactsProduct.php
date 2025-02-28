<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Contracts;

interface OpenfoodfactsProduct
{
    /**
     * @param  string|null  $key  If null is given the whole array is returned
     * @param  mixed|null  $default  Default value if the key is empty
     */
    public function data(?string $key = null, mixed $default = null): mixed;
}
