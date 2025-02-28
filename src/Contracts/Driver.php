<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Contracts;

use Ccharz\LaravelOpenfoodfactsReader\Exceptions\ProductNotFoundException;
use Ccharz\LaravelOpenfoodfactsReader\Exceptions\UnableToReadDataException;

interface Driver
{
    /**
     * @param  string  $barcode  The barcode of the product to be fetched
     *
     * @throws UnableToReadDataException
     * @throws ProductNotFoundException
     */
    public function product(string $barcode): ?OpenfoodfactsProduct;

    /**
     * @param  array<string,string>  $parameters
     * @return array<string, mixed>
     *
     * @throws UnableToReadDataException
     */
    public function search(array $parameters): array;
}
