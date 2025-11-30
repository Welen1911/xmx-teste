<?php

namespace App\Actions\DummyJson;

use App\Helpers\BulkImporter;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\DummyJsonService;

class ImportCommentsAction
{
    public function __construct(
        protected DummyJsonService $dummyJsonService,
        protected BulkImporter $bulkImporter
    ) {}

    public function execute($output): void
    {
        try {
            $comments = $this->dummyJsonService->getComments()['comments'] ?? [];

            if (empty($comments)) {
                $output->writeln("<comment>Nenhum comentário encontrado.</comment>");
                return;
            }

            $users = $this->bulkImporter->mapByExternalId(
                new User(),
                collect($comments)->pluck('user.id')->unique()->toArray()
            );

            $posts = $this->bulkImporter->mapByExternalId(
                new Post(),
                collect($comments)->pluck('postId')->unique()->toArray()
            );

            $payload = collect($comments)->map(fn($c) => [
                'external_id' => $c['id'],
                'body'        => $c['body'],
                'likes'       => $c['likes'],
                'post_id'     => $posts[$c['postId']] ?? null,
                'user_id'     => $users[$c['user']['id']] ?? null,
            ])->toArray();

            $this->bulkImporter->upsert(
                new Comment(),
                $payload,
                ['external_id'],
                ['body', 'likes', 'post_id', 'user_id']
            );

            $output->writeln("<comment>Comentários importados com sucesso!</comment>");
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }
    }
}
