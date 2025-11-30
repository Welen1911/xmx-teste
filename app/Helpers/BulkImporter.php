<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BulkImporter
{
    public function mapByExternalId(Model $model, array $externalIds): array
    {
        return $model->newQuery()
            ->whereIn('external_id', $externalIds)
            ->pluck('id', 'external_id')
            ->toArray();
    }

    public function upsert(Model $model, array $data, array $uniqueBy, array $updateColumns): void
    {
        DB::transaction(fn() =>
            $model->newQuery()->upsert($data, $uniqueBy, $updateColumns)
        );
    }

    public function withTimestamps(array $rows): array
    {
        return collect($rows)->map(function ($row) {
            return array_merge($row, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        })->toArray();
    }
}
