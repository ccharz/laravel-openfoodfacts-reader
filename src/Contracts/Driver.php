<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Contracts;

interface Driver
{
    /**
     * @param  string  $barcode The barcode of the product to be fetched
     *
     * @throws Ccharz\LaravelOpenfoodfactsReader\Exceptions\UnableToReadDataException
     * @throws Ccharz\LaravelOpenfoodfactsReader\Exceptions\ProductNotFoundException
     */
    public function product(string $barcode): ?Product;

    /**
     * @throws Ccharz\LaravelOpenfoodfactsReader\Exceptions\UnableToReadDataException
     */
    public function search(array $parameters): array;
}
