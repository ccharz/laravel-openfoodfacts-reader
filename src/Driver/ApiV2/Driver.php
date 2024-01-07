<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Driver\ApiV2;

use Ccharz\LaravelOpenfoodfactsReader\Contracts\Driver as DriverContract;
use Ccharz\LaravelOpenfoodfactsReader\Exceptions\ProductNotFoundException;
use Ccharz\LaravelOpenfoodfactsReader\Exceptions\UnableToReadDataException;
use Illuminate\Support\Facades\Http;
use Throwable;

class Driver implements DriverContract
{
    const URL = 'https://world.openfoodfacts.org/api/v2/';

    protected function getFromAPI(string $url, array $query = []): array
    {
        $user_agent = config('openfoodfactsreader.user_agent');

        if (empty($user_agent)) {
            throw new UnableToReadDataException('Missing user agent in configuration');
        }

        try {
            $result = Http::acceptJson()
                ->withHeaders([
                    'User-Agent' => $user_agent,
                ])
                ->acceptJson()
                ->get(self::URL.$url, $query)
                ->throw()
                ->json();
        } catch (Throwable $e) {
            throw new UnableToReadDataException('', 0, $e);
        }

        if (empty($result)) {
            throw new UnableToReadDataException('Empty result');
        }

        return $result;
    }

    public function product(string $barcode): ?OpenfoodfactsProduct
    {
        $product_data = $this->getFromAPI('product/'.$barcode);

        if (! isset($product_data['status']) || ($product_data['status'] !== 1) || ! isset($product_data['product'])) {
            throw new ProductNotFoundException();
        }

        return new OpenfoodfactsProduct($product_data['product']);
    }

    public function search(array $parameters, int $page = 1, array $fields = ['code', 'product_name', 'product_name_de']): array
    {
        $search_result = $this->getFromAPI(
            'search',
            [
                ...$parameters,
                'fields' => implode(',', $fields),
                'page' => $page,
            ]
        );

        return [
            'meta' => [
                'current_page' => $search_result['page'],
                'last_page' => $search_result['page_count'],
                'per_page' => $search_result['page_size'],
                'total' => $search_result['count'],
            ],
            'data' => $search_result['products'],
        ];
    }
}
