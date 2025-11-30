<?php

namespace App\Actions\DummyJson;

use App\Helpers\BulkImporter;
use App\Helpers\PostPivotSyncHelper;
use App\Helpers\TagImporter;
use App\Models\Post;
use App\Models\User;
use App\Services\DummyJsonService;

class ImportPostsAction
{
    public function __construct(
        protected DummyJsonService $dummyJsonService,
        protected BulkImporter $bulkImporter,
        protected TagImporter $tagImporter,
        protected PostPivotSyncHelper $pivotHelper
    ) {}

    public function execute($output): void
    {
        try {
            $posts = $this->dummyJsonService->getPosts()['posts'] ?? [];

            $userMap = User::pluck('id', 'external_id')->toArray();

            $tagMap = $this->tagImporter->importAndMap($posts);

            $payload = collect($posts)->map(function ($p) use ($userMap) {
                return [
                    'external_id' => $p['id'],
                    'title'       => $p['title'],
                    'body'        => $p['body'],
                    'likes'       => $p['reactions']['likes'],
                    'dislikes'    => $p['reactions']['dislikes'],
                    'views'       => $p['views'],
                    'user_id'     => $userMap[$p['userId']] ?? null,
                ];
            })->toArray();

            $payload = $this->bulkImporter->withTimestamps($payload);

            $this->bulkImporter->upsert(
                new Post(),
                $payload,
                ['external_id'],
                ['title', 'body', 'likes', 'dislikes', 'views', 'user_id', 'updated_at']
            );

            $postMap = Post::pluck('id', 'external_id')->toArray();

            $this->pivotHelper->sync($posts, $postMap, $tagMap);

            $output->writeln("<comment>Posts importados com sucesso!</comment>");
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }
    }
}
