<?php

namespace App\Console\Commands;

use App\Jobs\BeneficiariesInstruExportJob;
use App\Jobs\BeneficiariesMoniExportJob;
use App\Jobs\BeneficiaryNotAssociateExportJob;
use App\Jobs\BinnacleImpactExportJob;
use App\Jobs\BinnaclePactMonitorsExportJob;
use App\Jobs\CulturalSeedbedBeneficiaryExportJob;
use App\Jobs\CulturalSeedBedExportJob;
use App\Jobs\PecsExportJob;
use App\Jobs\PedagogicalsExportJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class ExcelGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'g:e {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generamos los excel y les pasamos el tipo de Excel a generar';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // ensures that the progress bar is at 100%
        $pass = $this->output->createProgressBar(2);
        $type = $this->argument('type');

        // Controlar el tiempo
        $startTime = microtime(true);
        $result = null;
        // Mostrar el tiempo
        $timeNow = Carbon::createFromTimestamp($startTime);
        // Convertido
        $message = $timeNow->format('Y-m-d H:i:s');
        $this->info('............................................');
        $this->info('La hora de ejecucion es ' . $message);
        switch ($type) {
            case 'info':
                $this->info('............................................');
                $this->info("El número corresponde al informe a generar\n1 => BinnaclePactMonitorsExpot\n2 => PecsExpot\n3 => CulturalSeedBedExpot\n4 => BeneficiariesInstruExpot\n5 => BeneficiariesMoniExpot\n6 => BeneficiaryNotAssociateExpot\n7 => CulturalSeedbedBeneficiaryExpot\n8 => BinnacleImpactExport\n");
                $this->info('............................................');
                break;
            case '1':
                $this->info('............................................');
                $this->info('Generando proceso en segundo plano');
                $this->info('............................................');
                $this->info('Creando y guardando el excel');
                $this->info('............................................');
                BinnaclePactMonitorsExportJob::dispatch()->onQueue('BinnaclePactMonitorsExport');
                $result = Artisan::call('queue:work', [
                    '--queue' => 'BinnaclePactMonitorsExport',
                    '--timeout' => '1200000'
                ]);
                break;
            case '2':
                $this->info('............................................');
                $this->info('Generando proceso en segundo plano');
                $this->info('............................................');
                $this->info('Creando y guardando el excel');
                $this->info('............................................');
                PecsExportJob::dispatch()->onQueue('PecsExport');
                $result = Artisan::call('queue:work', [
                    '--queue' => 'PecsExport',
                    '--timeout' => '1200000'
                ]);
                break;
            case '3':
                $this->info('............................................');
                $this->info('Generando proceso en segundo plano');
                $this->info('............................................');
                $this->info('Creando y guardando el excel');
                $this->info('............................................');
                CulturalSeedBedExportJob::dispatch()->onQueue('CulturalSeedBedExport');
                $result = Artisan::call('queue:work', [
                    '--queue' => 'CulturalSeedBedExport',
                    '--timeout' => '1200000'
                ]);
                break;
            case '4':
                $this->info('............................................');
                $this->info('Generando proceso en segundo plano');
                $this->info('............................................');
                $this->info('Creando y guardando el excel');
                $this->info('............................................');
                BeneficiariesInstruExportJob::dispatch()->onQueue('BeneficiariesInstruExport');
                $result = Artisan::call('queue:work', [
                    '--queue' => 'BeneficiariesInstruExport',
                    '--timeout' => '1200000'
                ]);
                break;
            case '5':
                $this->info('............................................');
                $this->info('Generando proceso en segundo plano');
                $this->info('............................................');
                $this->info('Creando y guardando el excel');
                $this->info('............................................');
                BeneficiariesMoniExportJob::dispatch()->onQueue('BeneficiariesMoniExport');
                $result = Artisan::call('queue:work', [
                    '--queue' => 'BeneficiariesMoniExport',
                    '--timeout' => '1200000'
                ]);
                break;
            case '6':
                $this->info('............................................');
                $this->info('Generando proceso en segundo plano');
                $this->info('............................................');
                $this->info('Creando y guardando el excel');
                $this->info('............................................');
                BeneficiaryNotAssociateExportJob::dispatch()->onQueue('BeneficiaryNotAssociateExport');
                $result = Artisan::call('queue:work', [
                    '--queue' => 'BeneficiaryNotAssociateExport',
                    '--timeout' => '1200000'
                ]);
                break;
            case '7':
                $this->info('............................................');
                $this->info('Generando proceso en segundo plano');
                $this->info('............................................');
                $this->info('Creando y guardando el excel');
                $this->info('............................................');
                CulturalSeedbedBeneficiaryExportJob::dispatch()->onQueue('CulturalSeedbedBeneficiaryExport');
                $result = Artisan::call('queue:work', [
                    '--queue' => 'CulturalSeedbedBeneficiaryExport',
                    '--timeout' => '1200000'
                ]);
                break;
            case '8':
                $this->info('............................................');
                $this->info('Generando proceso en segundo plano');
                $this->info('............................................');
                $this->info('Creando y guardando el excel');
                $this->info('............................................');
                BinnacleImpactExportJob::dispatch()->onQueue('BinnacleImpactExport');
                $result = Artisan::call('queue:work', [
                    '--queue' => 'BinnacleImpactExport',
                    '--timeout' => '1200000'
                ]);
                break;
            case '9':
                $this->info('............................................');
                $this->info('Generando proceso en segundo plano');
                $this->info('............................................');
                $this->info('Creando y guardando el excel');
                $this->info('............................................');
                PedagogicalsExportJob::dispatch()->onQueue('pedagogicals');
                $result = Artisan::call('queue:work', [
                    '--queue' => 'pedagogicals',
                    '--timeout' => '1200000'
                ]);
                break;
            case 'all':
                    $this->info('............................................');
                    $this->info('Generando proceso en segundo plano');
                    $this->info('............................................');
                    $this->info('Creando y guardando el excel');
                    $this->info('............................................');
                    BinnaclePactMonitorsExportJob::dispatch()->onQueue('BinnaclePactMonitorsExport');
                    $result = Artisan::call('queue:work', [
                        '--queue' => 'BinnaclePactMonitorsExport',
                        '--timeout' => '1200000'
                    ]);
                    $message = $timeNow->format('Y-m-d H:i:s');
                    $this->info('............................................');
                    $this->info('La hora de ejecucion es ' . $message);
                    PecsExportJob::dispatch()->onQueue('PecsExport');
                    $result = Artisan::call('queue:work', [
                        '--queue' => 'PecsExport',
                        '--timeout' => '1200000'
                    ]);
                    $message = $timeNow->format('Y-m-d H:i:s');
                    $this->info('............................................');
                    $this->info('La hora de ejecucion es ' . $message);
                    CulturalSeedBedExportJob::dispatch()->onQueue('CulturalSeedBedExport');
                    $result = Artisan::call('queue:work', [
                        '--queue' => 'CulturalSeedBedExport',
                        '--timeout' => '1200000'
                    ]);
                    $message = $timeNow->format('Y-m-d H:i:s');
                    $this->info('............................................');
                    $this->info('La hora de ejecucion es ' . $message);
                    BeneficiariesInstruExportJob::dispatch()->onQueue('BeneficiariesInstruExport');
                    $result = Artisan::call('queue:work', [
                        '--queue' => 'BeneficiariesInstruExport',
                        '--timeout' => '1200000'
                    ]);
                    $message = $timeNow->format('Y-m-d H:i:s');
                    $this->info('............................................');
                    $this->info('La hora de ejecucion es ' . $message);
                    BeneficiariesMoniExportJob::dispatch()->onQueue('BeneficiariesMoniExport');
                    $result = Artisan::call('queue:work', [
                        '--queue' => 'BeneficiariesMoniExport',
                        '--timeout' => '1200000'
                    ]);
                    $message = $timeNow->format('Y-m-d H:i:s');
                    $this->info('............................................');
                    $this->info('La hora de ejecucion es ' . $message);
                    BeneficiaryNotAssociateExportJob::dispatch()->onQueue('BeneficiaryNotAssociateExport');
                    $result = Artisan::call('queue:work', [
                        '--queue' => 'BeneficiaryNotAssociateExport',
                        '--timeout' => '1200000'
                    ]);
                    $message = $timeNow->format('Y-m-d H:i:s');
                    $this->info('............................................');
                    $this->info('La hora de ejecucion es ' . $message);
                    CulturalSeedbedBeneficiaryExportJob::dispatch()->onQueue('CulturalSeedbedBeneficiaryExport');
                    $result = Artisan::call('queue:work', [
                        '--queue' => 'CulturalSeedbedBeneficiaryExport',
                        '--timeout' => '1200000'
                    ]);
                    $message = $timeNow->format('Y-m-d H:i:s');
                    $this->info('............................................');
                    $this->info('La hora de ejecucion es ' . $message);
                    BinnacleImpactExportJob::dispatch()->onQueue('BinnacleImpactExport');
                    $result = Artisan::call('queue:work', [
                        '--queue' => 'BinnacleImpactExport',
                        '--timeout' => '1200000'
                    ]);
                    break;
            default:
                $this->error('...........................................');
                $this->error('........... Opcion no permitida ...........');
                $this->error('...........................................');
                break;
        }
        // Mostrar duracion
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        $this->info("Execution time: {$executionTime} seconds");
        // Si el resultado es diferente de cero, muestra el mensaje de error en la consola
        if ($result !== 0) {
            $this->error(Artisan::output());
        }
        // Mostramos la barra final
        $pass->finish();
        return Command::SUCCESS;
    }
}
