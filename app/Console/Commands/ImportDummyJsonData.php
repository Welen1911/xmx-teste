<?php

namespace App\Console\Commands;

use App\Actions\DummyJson\ImportCommentsAction;
use App\Actions\DummyJson\ImportPostsAction;
use App\Actions\DummyJson\ImportUsersAction;
use Illuminate\Console\Command;

class ImportDummyJsonData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dummyjson:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import dummy data from DummyJSON API';

    /**
     * Execute the console command.
     */
    public function handle(
        ImportUsersAction $importUsersAction,
        ImportPostsAction $importPostsAction,
        ImportCommentsAction $importCommentsAction
    ): void
    {
        $this->info("🚀 Iniciando importação...");

        $importUsersAction->execute($this->output);
        $importPostsAction->execute($this->output);
        $importCommentsAction->execute($this->output);

        $this->info("✔️ Finalizado com sucesso!");
    }
}
