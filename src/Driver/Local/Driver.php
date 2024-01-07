<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Driver\Local;

use Ccharz\LaravelOpenfoodfactsReader\Contracts\Driver as DriverContract;
use Ccharz\LaravelOpenfoodfactsReader\Exceptions\ProductNotFoundException;

class Driver implements DriverContract
{
    public function product(string $barcode): OpenfoodfactsProduct
    {
        $model = config('openfoodfactsreader.model');

        $product = $model::where('code', $barcode)
            ->first();

        if (! $product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function search(array $parameters, int $page = 1): array
    {
        $model = config('openfoodfactsreader.model');

        $query = $model::query();

        $search_parameters = $model::$search_parameters;

        foreach ($parameters as $key => $value) {
            if (in_array($key, $search_parameters)) {
                $query->where($key, $value);
            }
        }

        $result = $query->paginate($page);

        return [
            'meta' => [
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'per_page' => $result->perPage(),
                'total' => $result->total(),
            ],
            'data' => $result->items(),
        ];
    }
}
