<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class setConsecutiveInscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:consecutiveInscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para actualizar los consecutivos de inscripciones';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // ensures that the progress bar is at 100%
        $pass = $this->output->createProgressBar(6);
        $this->info('Ya inicio el proceso espera poco...');
        $pass->start();
        $this->newLine();
        $this->info('Ejecutando consulta y actulización SQL');
        $this->info('..........................................');
        $this->info('En breve se actualizaran los datos');
        $this->info('..........................................');
        $this->info('UPDATE inscriptions SET consecutive = CONCAT("F", id)');
        $this->info('..........................................');
        DB::update('UPDATE inscriptions SET consecutive = CONCAT("F", id)');
        $pass->finish();
        return Command::SUCCESS;
    }
}
