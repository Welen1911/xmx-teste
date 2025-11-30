<?php

namespace Tests\Unit\Actions;

use App\Actions\DummyJson\ImportUsersAction;
use App\Helpers\BulkImporter;
use App\Models\User;
use App\Services\DummyJsonService;
use Mockery;
use Tests\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class ImportUsersActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_imports_users_successfully()
    {
        $dummyJsonService = Mockery::mock(DummyJsonService::class);
        $dummyJsonService->shouldReceive('getUsers')
            ->once()
            ->andReturn([
                'users' => [
                    [
                        'id' => 1,
                        'firstName' => 'John',
                        'lastName' => 'Doe',
                        'email' => 'john@example.com',
                        'phone' => '9999-9999',
                        'image' => 'img.jpg',
                        'birthDate' => '1990-01-01',
                        'address' => ['city' => 'NY'],
                    ]
                ]
            ]);

        $bulkImporter = Mockery::mock(BulkImporter::class);
        $bulkImporter->shouldReceive('upsert')
            ->once()
            ->withArgs(function ($model, $payload, $uniqueBy, $updateColumns) {
                return $model instanceof User
                    && $uniqueBy === ['external_id']
                    && $updateColumns === [
                        'first_name', 'last_name', 'email', 'phone',
                        'image', 'birth_date', 'address', 'password'
                    ]
                    && $payload[0]['external_id'] === 1
                    && $payload[0]['first_name'] === 'John';
            });

        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->once()
            ->with("<comment>Usuários importados com sucesso!</comment>");

        $action = new ImportUsersAction($dummyJsonService, $bulkImporter);

        $action->execute($output);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_outputs_message_when_no_users_found()
    {
        $dummyJsonService = Mockery::mock(DummyJsonService::class);
        $dummyJsonService->shouldReceive('getUsers')
            ->once()
            ->andReturn(['users' => []]);

        $bulkImporter = Mockery::mock(BulkImporter::class);
        $bulkImporter->shouldNotReceive('upsert');

        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->once()
            ->with("<comment>Nenhum usuário encontrado.</comment>");

        $action = new ImportUsersAction($dummyJsonService, $bulkImporter);

        $action->execute($output);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_outputs_error_when_exception_is_thrown()
    {
        $dummyJsonService = Mockery::mock(DummyJsonService::class);
        $dummyJsonService->shouldReceive('getUsers')
            ->once()
            ->andThrow(new \Exception("Erro inesperado"));

        $bulkImporter = Mockery::mock(BulkImporter::class);
        $bulkImporter->shouldNotReceive('upsert');

        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->once()
            ->with("<error>Erro inesperado</error>");

        $action = new ImportUsersAction($dummyJsonService, $bulkImporter);

        $action->execute($output);

        $this->assertTrue(true);
    }
}
