<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Tests;

use Ccharz\LaravelOpenfoodfactsReader\Driver\Local\OpenfoodfactsProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class ImportDatabaseCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_import_the_database(): void
    {
        Storage::fake();

        $this
            ->artisan('openfoodfactsreader:import '.$this->getTestDataPath('local_products_01.jsonl'))
            ->expectsOutput('Import finished')
            ->assertSuccessful();

        $this->assertDatabaseCount('openfoodfacts_products', 10);

        $product = OpenfoodfactsProduct::where('code', '0000000000123')->first();

        $this->assertSame('0000000000123', $product->data('_id'));

        $this->assertCount(10, Storage::allFiles());
    }

    public function test_it_fails_without_a_file(): void
    {
        Storage::fake();

        $this->expectExceptionMessage('Error reading file');

        $this
            ->artisan('openfoodfactsreader:import 1234.jsonl')
            ->assertFailed();
    }
}
