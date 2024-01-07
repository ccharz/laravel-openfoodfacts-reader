<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Console;

use Exception;
use Illuminate\Console\Command;

class ImportDatabase extends Command
{
    protected $signature = 'openfoodfactsreader:import {file}';

    protected $description = 'Import the openfoodfacts data to the local database';

    private function readFile(string $file)
    {
        if (! $handle = @\fopen($file, 'r')) {
            throw new Exception('Error reading file');
        }

        while ($line = \fgets($handle)) {
            yield $line;
        }

        \fclose($handle);

        unset($handle);
    }

    public function handle(): void
    {
        $model = config('openfoodfactsreader.model');

        $file = $this->argument('file');

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
