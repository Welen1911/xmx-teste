<?php

namespace Tests\Unit\Actions;

use App\Actions\DummyJson\ImportCommentsAction;
use App\Helpers\BulkImporter;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\DummyJsonService;
use Mockery;
use Tests\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommentsActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_imports_comments_successfully()
    {
        $dummyJsonService = Mockery::mock(DummyJsonService::class);

        $dummyJsonService->shouldReceive('getComments')
            ->once()
            ->andReturn([
                'comments' => [
                    [
                        'id' => 10,
                        'body' => 'Great post!',
                        'likes' => 5,
                        'postId' => 100,
                        'user' => ['id' => 200]
                    ]
                ]
            ]);

        $bulkImporter = Mockery::mock(BulkImporter::class);

        $bulkImporter->shouldReceive('mapByExternalId')
            ->once()
            ->with(Mockery::type(User::class), [200])
            ->andReturn([200 => 2]);

        $bulkImporter->shouldReceive('mapByExternalId')
            ->once()
            ->with(Mockery::type(Post::class), [100])
            ->andReturn([100 => 3]);

        $bulkImporter->shouldReceive('upsert')
            ->once()
            ->withArgs(function ($model, $payload, $uniqueBy, $updateColumns) {
                return $model instanceof Comment
                    && $uniqueBy === ['external_id']
                    && $updateColumns === ['body', 'likes', 'post_id', 'user_id']
                    && $payload[0]['external_id'] === 10
                    && $payload[0]['body'] === 'Great post!'
                    && $payload[0]['likes'] === 5
                    && $payload[0]['post_id'] === 3
                    && $payload[0]['user_id'] === 2;
            });

        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->once()
            ->with("<comment>Comentários importados com sucesso!</comment>");

        $action = new ImportCommentsAction($dummyJsonService, $bulkImporter);

        $action->execute($output);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_outputs_message_when_no_comments_found()
    {
        $dummyJsonService = Mockery::mock(DummyJsonService::class);
        $dummyJsonService->shouldReceive('getComments')
            ->once()
            ->andReturn(['comments' => []]);

        $bulkImporter = Mockery::mock(BulkImporter::class);
        $bulkImporter->shouldNotReceive('upsert');

        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->once()
            ->with("<comment>Nenhum comentário encontrado.</comment>");

        $action = new ImportCommentsAction($dummyJsonService, $bulkImporter);
        $action->execute($output);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_outputs_error_when_exception_is_thrown()
    {
        $dummyJsonService = Mockery::mock(DummyJsonService::class);
        $dummyJsonService->shouldReceive('getComments')
            ->once()
            ->andThrow(new \Exception("Erro inesperado"));

        $bulkImporter = Mockery::mock(BulkImporter::class);
        $bulkImporter->shouldNotReceive('upsert');

        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->once()
            ->with("<error>Erro inesperado</error>");

        $action = new ImportCommentsAction($dummyJsonService, $bulkImporter);
        $action->execute($output);

        $this->assertTrue(true);
    }
}
