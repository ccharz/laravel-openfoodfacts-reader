<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Tests;

use Ccharz\LaravelOpenfoodfactsReader\Driver\ApiV2\Driver as ApiV2Driver;
use Ccharz\LaravelOpenfoodfactsReader\Driver\ApiV2\OpenfoodfactsProduct as ApiV2OpenfoodfactsProduct;
use Ccharz\LaravelOpenfoodfactsReader\Exceptions\ProductNotFoundException;
use Ccharz\LaravelOpenfoodfactsReader\Exceptions\UnableToReadDataException;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class ApiV2DriverTest extends TestCase
{
    protected function setUserAgent(): void
    {
        Config::set(
            'openfoodfactsreader.user_agent',
            'LaravelOpenfoodfactsReader/1.0 (office@cw-software.at)'
        );
    }

    protected function fakeProductApi(ResponseSequence $responseSequence): void
    {
        Http::preventStrayRequests();

        Http::fake([
            'https://world.openfoodfacts.org/api/v2/product/*' => $responseSequence,
        ]);

        $this->setUserAgent();
    }

    protected function fakeSearchApi(ResponseSequence $responseSequence): void
    {
        Http::preventStrayRequests();

        Http::fake([
            'https://world.openfoodfacts.org/api/v2/search*' => $responseSequence,
        ]);

        $this->setUserAgent();
    }

    public function test_it_can_fetch_a_product(): void
    {
        $this->fakeProductApi(Http::sequence()
            ->push($this->getTestData('product_01.json'))
        );

        $driver = new ApiV2Driver;
        $result = $driver->product('3017620422003');

        $this->assertInstanceOf(ApiV2OpenfoodfactsProduct::class, $result);

        $this->assertSame('Nutella', $result->product_name_de);

        $this->assertIsArray($result->data());

        Http::assertSent(function (Request $request) {
            return $request->hasHeader(
                'User-Agent',
                'LaravelOpenfoodfactsReader/1.0 (office@cw-software.at)'
            );
        });
    }

    public function test_it_throws_with_empty_response(): void
    {
        $this->setUserAgent();

        Http::fake();

        $this->expectExceptionMessage('Empty result');

        (new ApiV2Driver)->product('3017620422003');
    }

    public function test_it_throws_without_product_result(): void
    {
        $this->fakeProductApi(Http::sequence()
            ->push(['status' => 0])
        );

        $this->expectException(ProductNotFoundException::class);

        (new ApiV2Driver)->product('3017620422003');
    }

    public function test_it_throws_without_user_agent(): void
    {
        Http::fake();

        $this->expectExceptionMessage('Missing user agent in configuration');

        (new ApiV2Driver)->product('3017620422003');
    }

    public function test_it_throws_with_error(): void
    {
        $this->fakeProductApi(Http::sequence()
            ->push(['status' => 0], 500)
        );

        $this->expectException(UnableToReadDataException::class);

        (new ApiV2Driver)->product('3017620422003');
    }

    public function test_it_can_search_a_product(): void
    {
        $this->fakeSearchApi(Http::sequence()
            ->push($this->getTestData('search_01.json'))
        );

        $result = (new ApiV2Driver)->search(['brands_tags' => 'Spar']);

        $this->assertCount(24, $result['data']);
        $this->assertSame(24, $result['meta']['last_page']);
        $this->assertSame(1, $result['meta']['current_page']);
    }
}
