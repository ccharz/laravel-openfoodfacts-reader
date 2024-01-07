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
        $file = $this->argument('file');

        $model = config('openfoodfactsreader.model');

        foreach ($this->readFile($file) as $json) {
            $model::storeFromJson($json);
        }

        $this->info('Import finished');
    }
}
