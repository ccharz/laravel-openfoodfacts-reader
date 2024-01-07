<?php

declare(strict_types=1);

namespace Ccharz\LaravelOpenfoodfactsReader\Driver\Local;

use Ccharz\LaravelOpenfoodfactsReader\Contracts\OpenfoodfactsProduct as OpenfoodfactsProductContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class OpenfoodfactsProduct extends Model implements OpenfoodfactsProductContract
{
    protected array $data;

    public static array $search_parameters = [
        'code',
    ];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'imported_at' => 'datetime',
    ];

    public function getDataAttribute(): array
    {
        if (! isset($this->data)) {
            $data = Storage::disk(config('openfoodfactsreader.disk'))
                ->get(static::productDataPath($this->id));

            $this->data = \json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        }

        return $this->data;
    }

    public function data(?string $key = null, mixed $default = null): mixed
    {
        if (! isset($key)) {
            return $this->getDataAttribute();
        }

        return Arr::get($this->getDataAttribute(), $key, $default);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (! $this->offsetExists($key)) {
            return $this->data($key);
        }

        return parent::__get($key);
    }

    public static function productDataPath(string $id): string
    {
        return config('openfoodfactsreader.path').'/'.substr($id, 0, 3).'/'.$id.'.json';
    }

    public static function storeFromJson(string $json): void
    {
        $product = \json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (empty($product['_id'])) {
            return;
        }

        $id = \md5(strval($product['_id']));

        OpenfoodfactsProduct::upsert(
            [
                [
                    'id' => $id,
                    'code' => $product['code'],
                    'created_at' => Carbon::parse($product['created_t']),
                    'updated_at' => Carbon::parse($product['last_modified_t']),
                    'imported_at' => Carbon::now(),
                ],
            ],
            ['id'],
            ['code', 'updated_at', 'imported_at']
        );

        Storage::disk(config('openfoodfactsreader.disk'))
            ->put(self::productDataPath($id), $json);
    }
}
