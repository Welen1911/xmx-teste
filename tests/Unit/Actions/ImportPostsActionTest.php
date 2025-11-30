<?php

namespace Tests\Unit\Actions;

use App\Actions\DummyJson\ImportPostsAction;
use App\Helpers\BulkImporter;
use App\Helpers\PostPivotSyncHelper;
use App\Helpers\TagImporter;
use App\Models\Post;
use App\Models\User;
use App\Services\DummyJsonService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Symfony\Component\Console\Output\OutputInterface;
use Tests\TestCase;

class ImportPostsActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_imports_posts_successfully()
    {
        $user = User::factory()->create([
            'id'          => 1,
            'external_id' => 55,
        ]);

        $dummyService = Mockery::mock(DummyJsonService::class);
        $dummyService->shouldReceive('getPosts')->once()->andReturn([
            'posts' => [
                [
                    'id' => 10,
                    'title' => 'Test post',
                    'body' => 'Lorem ipsum',
                    'views' => 100,
                    'reactions' => ['likes' => 5, 'dislikes' => 1],
                    'userId' => 55,
                    'tags' => ['php', 'laravel'],
                ],
            ],
        ]);

        $bulkImporter = Mockery::mock(BulkImporter::class);
        $bulkImporter->shouldReceive('withTimestamps')
            ->once()
            ->andReturnUsing(fn($payload) => $payload);

        $bulkImporter->shouldReceive('upsert')
            ->once()
            ->withArgs(function ($model, $payload) {
                return $payload[0]['external_id'] === 10
                    && $payload[0]['title'] === 'Test post';
            });

        Post::create([
            'id'          => 1,
            'external_id' => 10,
            'title'       => 'placeholder',
            'body'        => 'test',
            'likes'       => 0,
            'dislikes'    => 0,
            'views'       => 0,
            'user_id'     => 1,
        ]);

        $tagImporter = Mockery::mock(TagImporter::class);
        $tagImporter->shouldReceive('importAndMap')
            ->once()
            ->andReturn([
                'php' => 1,
                'laravel' => 2,
            ]);

        $pivotHelper = Mockery::mock(PostPivotSyncHelper::class);
        $pivotHelper->shouldReceive('sync')
            ->once();

        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->once()
            ->with("<comment>Posts importados com sucesso!</comment>");

        
        $action = new ImportPostsAction(
            $dummyService,
            $bulkImporter,
            $tagImporter,
            $pivotHelper
        );

        
        $action->execute($output);

        $this->assertDatabaseHas('users', ['external_id' => 55]);
        $this->assertDatabaseHas('posts', ['external_id' => 10]);
    }

    /** @test */
    public function it_outputs_error_message_on_exception()
    {
        $dummyService = Mockery::mock(DummyJsonService::class);
        $dummyService->shouldReceive('getPosts')
            ->once()
            ->andThrow(new \Exception('falhou'));

        $bulkImporter = Mockery::mock(BulkImporter::class);
        $tagImporter = Mockery::mock(TagImporter::class);
        $pivotHelper = Mockery::mock(PostPivotSyncHelper::class);

        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->once()
            ->with("<error>falhou</error>");

        $action = new ImportPostsAction(
            $dummyService,
            $bulkImporter,
            $tagImporter,
            $pivotHelper
        );

        $action->execute($output);

        $this->assertTrue(true);
    }
}
