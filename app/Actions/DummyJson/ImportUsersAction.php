<?php

namespace App\Actions\DummyJson;

use App\Helpers\BulkImporter;
use App\Models\User;
use App\Services\DummyJsonService;

class ImportUsersAction
{
    public function __construct(
        protected DummyJsonService $dummyJsonService,
        protected BulkImporter $bulkImporter
    ) {}

    public function execute($output): void
    {
        try {
            $users = $this->dummyJsonService->getUsers()['users'] ?? [];

            if (empty($users)) {
                $output->writeln("<comment>Nenhum usuário encontrado.</comment>");
                return;
            }

            $payload = collect($users)->map(fn($u) => [
                'external_id' => $u['id'],
                'first_name' => $u['firstName'],
                'last_name' => $u['lastName'],
                'email' => $u['email'],
                'phone' => $u['phone'],
                'image' => $u['image'],
                'birth_date' => $u['birthDate'],
                'address' => json_encode($u['address']),
                'password' => config('app.default_password'),
            ])->toArray();

            $this->bulkImporter->upsert(
                new User(),
                $payload,
                ['external_id'],
                ['first_name', 'last_name', 'email', 'phone', 'image', 'birth_date', 'address', 'password']
            );

            $output->writeln("<comment>Usuários importados com sucesso!</comment>");
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }
    }
}
