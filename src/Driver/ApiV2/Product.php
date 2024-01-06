<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Driver\ApiV2;

use Ccharz\LaravelOpenfoodfactsReader\LaravelOpenfoodfactsProduct;

class Product extends LaravelOpenfoodfactsProduct
{
    public function __construct(public string $code)
    {
    }

    public static function fromArray(array $data): self
    {
        $product = new static($data['code']);
        $product->setData($data);

        return $product;
    }
}
