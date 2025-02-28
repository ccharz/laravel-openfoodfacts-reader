<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Console;

use Ccharz\LaravelOpenfoodfactsReader\Driver\Local\OpenfoodfactsProduct;
use Exception;
use Generator;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ImportDatabase extends Command
{
    protected $signature = 'openfoodfactsreader:import {file}';

    protected $description = 'Import the openfoodfacts data to the local database';

    /**
     * @return Generator<string>
     *
     * @throws Exception
     */
    private function readFile(string $file): Generator
    {
        if ($handle = @\fopen($file, 'r')) {
            while ($line = \fgets($handle)) {
                yield $line;
            }

            \fclose($handle);

            unset($handle);
        }
    }

    public function handle(): void
    {
        /** @var class-string<OpenfoodfactsProduct> $model */
        $model = config('openfoodfactsreader.model');

        $file = $this->argument('file');

        if (! is_string($file) || ! file_exists($file)) {
            throw new FileNotFoundException('Error reading file');
        }

        $bar = $this->output->createProgressBar();

        $bar->start();

        foreach ($this->readFile($file) as $json) {
            $model::storeFromJson($json);

            $bar->advance();
        }

        $bar->finish();

        $this->info('Import finished');
    }
}
