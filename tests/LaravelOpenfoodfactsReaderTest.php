<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Tests;

use Ccharz\LaravelOpenfoodfactsReader\Driver\ApiV2\Driver as ApiV2Driver;
use Ccharz\LaravelOpenfoodfactsReader\Exceptions\UnableToReadDataException;
use Ccharz\LaravelOpenfoodfactsReader\LaravelOpenfoodfactsReader;

class LaravelOpenfoodfactsReaderTest extends TestCase
{
    public function test_it_can_access_the_default_driver(): void
    {
        $test = app(LaravelOpenfoodfactsReader::class)->driver();

        $this->assertInstanceOf(ApiV2Driver::class, $test);
    }

    public function test_it_forwards_calls(): void
    {
        $this->expectException(UnableToReadDataException::class);

        app(LaravelOpenfoodfactsReader::class)->product('1234');
    }

    public function test_it_reuses_driver(): void
    {
        $test = app(LaravelOpenfoodfactsReader::class)->driver();

        $this->assertInstanceOf(ApiV2Driver::class, $test);

        $test_2 = app(LaravelOpenfoodfactsReader::class)->driver();

        $this->assertSame($test_2, $test);
    }

    public function test_it_can_use_a_driver_by_string(): void
    {
        $test = app(LaravelOpenfoodfactsReader::class)->driver('v2');

        $this->assertInstanceOf(ApiV2Driver::class, $test);
    }

    public function test_it_fails_on_invalid_driver(): void
    {
        $this->expectExceptionMessage('Invalid driver');

        app(LaravelOpenfoodfactsReader::class)->driver('vtest');
    }
}
