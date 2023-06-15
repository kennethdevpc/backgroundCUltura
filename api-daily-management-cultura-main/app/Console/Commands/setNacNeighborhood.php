<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class setNacNeighborhood extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:nacPec';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para asignar nacs a los barrios, según está en PEC';

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
        $this->info('UPDATE neighborhoods n JOIN pecs p ON n.id = p.neighborhood_id SET n.nac_id = p.nac_id');
        $this->info('..........................................');
        DB::update('UPDATE neighborhoods n JOIN pecs p ON n.id = p.neighborhood_id SET n.nac_id = p.nac_id');
        $pass->finish();
        return Command::SUCCESS;
    }
}
