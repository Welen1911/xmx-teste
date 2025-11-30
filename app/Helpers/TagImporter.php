<?php

namespace App\Helpers;

use App\Models\Tag;

class TagImporter
{
    public function importAndMap(array $posts): array
    {
        $existing = Tag::pluck('id', 'name')->toArray();

        $allTags = collect($posts)->pluck('tags')->flatten()->unique();

        $newTags = $allTags->diff(array_keys($existing));

        if ($newTags->isNotEmpty()) {
            Tag::insert(
                $newTags->map(fn($t) => ['name' => $t])->toArray()
            );

            $existing = Tag::pluck('id', 'name')->toArray();
        }

        return $existing;
    }
}
