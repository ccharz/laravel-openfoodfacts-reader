<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Tests;

use Ccharz\LaravelOpenfoodfactsReader\Driver\Local\Driver as LocalDriver;
use Ccharz\LaravelOpenfoodfactsReader\Driver\Local\OpenfoodfactsProduct;
use Ccharz\LaravelOpenfoodfactsReader\Exceptions\ProductNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class LocalDriverTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_fetch_a_product(): void
    {
        Storage::fake();

        OpenfoodfactsProduct::storeFromJson($this->getTestData('local_product_01.json'));

        $driver = new LocalDriver();
        $result = $driver->product('0000000001281');

        $this->assertInstanceOf(OpenfoodfactsProduct::class, $result);

        $this->assertSame('Tarte noix de coco', $result->product_name);

        $this->assertIsArray($result->data());
    }

    public function test_it_throws_without_product_result(): void
    {
        $this->expectException(ProductNotFoundException::class);

        (new LocalDriver())->product('3017620422003');
    }

    public function test_it_can_search_a_product(): void
    {
        OpenfoodfactsProduct::storeFromJson($this->getTestData('local_product_01.json'));

        $result = (new LocalDriver())->search(['code' => '0000000001281']);

        $this->assertCount(1, $result['data']);
        $this->assertSame(1, $result['meta']['last_page']);
        $this->assertSame(1, $result['meta']['current_page']);
    }

    public function test_it_doesnt_store_a_product_without_id(): void
    {
        OpenfoodfactsProduct::storeFromJson('{}');

        $this->assertSame(0, OpenfoodfactsProduct::count());
    }
}
