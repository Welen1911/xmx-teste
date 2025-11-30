<?php

namespace Tests\Feature\Console;

use App\Actions\DummyJson\ImportCommentsAction;
use App\Actions\DummyJson\ImportPostsAction;
use App\Actions\DummyJson\ImportUsersAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportDummyJsonDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_runs_all_import_actions()
    {
        $usersMock = $this->mock(ImportUsersAction::class);
        $postsMock = $this->mock(ImportPostsAction::class);
        $commentsMock = $this->mock(ImportCommentsAction::class);

        $usersMock->shouldReceive('execute')
            ->once()
            ->with(\Mockery::type('Illuminate\Console\OutputStyle'));

        $postsMock->shouldReceive('execute')
            ->once()
            ->with(\Mockery::type('Illuminate\Console\OutputStyle'));

        $commentsMock->shouldReceive('execute')
            ->once()
            ->with(\Mockery::type('Illuminate\Console\OutputStyle'));

        $this->artisan('dummyjson:import')
            ->expectsOutput('🚀 Iniciando importação...')
            ->expectsOutput('✔️ Finalizado com sucesso!')
            ->assertExitCode(0);
    }
}
