<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class PostPivotSyncHelper
{
    public function sync(array $posts, array $postMap, array $tagMap): void
    {
        foreach ($posts as $post) {
            $postId = $postMap[$post['id']] ?? null;
            if (!$postId) continue;

            $tagIds = array_map(fn($tag) => $tagMap[$tag], $post['tags']);

            DB::table('post_tag')
                ->where('post_id', $postId)
                ->delete();

            $payload = [];

            foreach ($tagIds as $tagId) {
                $payload[] = [
                    'post_id' => $postId,
                    'tag_id'  => $tagId,
                ];
            }

            DB::table('post_tag')->insert($payload);
        }
    }
}
