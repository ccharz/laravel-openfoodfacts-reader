<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Contracts;

interface Product
{
    public function setData(array $data): self;

    public static function fromArray(array $array): self;
}
