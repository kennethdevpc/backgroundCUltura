<?php

namespace App\Repositories;

use App\Exports\V1\AmbassadorExport;
use App\Exports\V1\AttendantsExport;
use App\Exports\V1\BeneficiariesExport;
use App\Exports\V1\BinnacleBeneficiaryMultipleSheetsExport;
use App\Exports\V1\BinnacleManagersExport;
use App\Exports\V1\BinnaclePactMonitorsExport;
use App\Exports\V1\BinnacleTerritorieExport;
use App\Exports\V1\CulturalCirculationExport;
use App\Exports\V1\CulturalEnsembleExport;
use App\Exports\V1\CulturalSeedBedExport;
use App\Exports\V1\DialogueTablesExport;
use App\Exports\V1\GroupExport;
use App\Exports\V1\InscriptionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\V1\VariablesExport;
use App\Exports\V1\LoginAccessExport;
use App\Exports\V1\ManagerMonitoringsExport;
use App\Exports\V1\ManagerSupervisionvisitsExport;
use App\Exports\V1\MethodologicalAccompanimentReport;
use App\Exports\V1\MethodologicalInstructionModelsExport;
use App\Exports\V1\MethodologicalMonitoringExport;
use App\Exports\V1\MethodologicalSheetsOneExport;
use App\Exports\V1\MethodologicalSheetsTwoExport;
use App\Exports\V1\MethodologicalStrengtheningExport;
use App\Exports\V1\MonitoringReportExport;
use App\Exports\V1\ParentSchoolsExport;
use App\Exports\V1\PecsExport;
use App\Exports\V1\PedagogicalsExport;
use App\Exports\V1\PollDesertionsExport;
use App\Exports\V1\PollsExport;
use App\Exports\V1\PsychopedagogicalLogBooksExport;
use App\Exports\V1\PsychosocialInstructionsExport;
use App\Exports\V1\RevisionExport;
use App\Exports\V1\StrengtheningOfMonitoringReport;
use App\Exports\V1\StrengtheningSuperMonInsReport;
use App\Exports\V1\UsersExport;
use App\Exports\V1\InputHistory;
use App\Exports\V1\PermissionExport;
use App\Exports\V1\RevisionExportMultipleSheets;
use App\Jobs\PedagogicalsExportJob;
use App\Models\AccessLogin;
use App\Models\Binnacle;
use App\Models\CulturalCirculation;
use App\Models\CulturalEnsemble;
use App\Models\CulturalSeedbed;
use App\Models\DialogueTables\DialogueTable;
use App\Models\Group;
use App\Models\Inscriptions\Beneficiary;
use App\Models\Inscriptions\Inscription;
use App\Models\Inscriptions\Pec;
use App\Models\ManagerMonitoring;
use App\Models\MethodologicalAccompaniment;
use App\Models\MethodologicalInstructionModel;
use App\Models\MethodologicalMonitoring;
use App\Models\MethodologicalSheetsOne;
use App\Models\MethodologicalSheetsTwo;
use App\Models\ParentSchools\ParentSchool;
use App\Models\Pedagogical;
use App\Models\Poll;
use App\Models\PollDesertion;
use App\Models\PsychopedagogicalLogbooks\PsychopedagogicalLogbook;
use App\Models\PsychosocialInstructions\PsychosocialInstruction;
use App\Models\StrengtheningOfMonitoring;
use App\Models\StrengtheningSupervisionMonitorsInstructors;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;
use Illuminate\Support\Facades\Response;

class ReportRepository
{

    function controlReport($request)
    {
        switch ($request->type_excel) {
            case 'pecs':
                return $this->exportJobExcel($request);
                break;
            case 'variables':
                return $this->exportVariable($request);
                break;
            case 'sesion':
                return $this->exportLoginAccess($request);
                break;
            case 'users':
                return  $this->exportUsers($request);
                break;
            case 'polls':
                return $this->exportPolls($request);
                break;
            case 'pollDesertions':
                return $this->exportPollDesertions($request);
                break;
            case 'pedagogicals':
                return  $this->exportJobExcel($request);
                break;
            case 'beneficiariesInstructor':
                return $this->exportJobExcel($request);
                break;
            case 'beneficiariesMonitor':
                return $this->exportJobExcel($request);
                break;
            case 'attendats':
                return $this->exportAttendats($request);
                break;
            case 'parentschools':
                return $this->exportParentschools($request);
                break;
            case 'dialogueTables':
                return $this->exportDialogueTables($request);
                break;
            case 'binnacles_monitor':
                return $this->exportJobExcel($request);
                break;
            case 'inscriptions':
                return $this->exportInscriptions($request);
                break;
            case 'managerMonitorings':
                return $this->exportManagerMonitorings($request);
                break;
            case 'methodologicalInstructionModels':
                return $this->exportMethodologicalInstructionModels($request);
                break;
            case 'binnacleManagers':
                return $this->exportBinnacleManagers($request);
                break;
            case 'psychosocialInstructions':
                return $this->exportPsychosocialInstructions($request);
                break;
            case 'psychopedagogicallogs':
                return $this->exportPsychopedagogicalLogBooks($request);
                break;
            case 'methodologicalSheetsOne':
                return $this->exportMethodologicalSheetsOne($request);
                break;
            case 'methodologicalsheetstwo':
                return $this->exportMethodologicalSheetsTwo($request);
                break;
            case 'ambassador':
                return $this->exporteAmbassador($request);
                break;
            case 'culturalEnsembles':
                return $this->exportCulturalEnsemble($request);
                break;
            case 'culturalCirculations':
                return $this->exporteCulturalCirculation($request);
                break;
            case 'culturalSeedbeds':
                return $this->exportJobExcel($request);
                break;
            case 'managerSupervisionvisit':
                return $this->exporteManagerSupervisionvisit($request);
            case 'revisions':
                return $this->exportRevisions($request);
            case 'monitoringReport':
                return $this->exporteMonitoringReport($request);
            case 'methodologicalStrengthening':
                return $this->exporteMethodologicalStrengthening($request);
            case 'binnacleTerritorie':
                return $this->exporteBinnacleTerritorie($request);
                break;
            case 'binnacleImpacts':
                return $this->exportBinnacleImpacts($request);
            case 'methodologicalMonitorings':
                return $this->exportMethodologicalMonitorings($request);
                break;
            case 'methodologicalAccompaniments':
                return $this->exportMethodologicalAccompaniments($request);
                break;
            case 'strengtheningSuperMonIns':
                return $this->exportStrengtheningSuperMonIns($request);
                break;
            case 'strengtheningOfMonitorings':
                return $this->exportStrengtheningOfMonitorings($request);
                break;
            case 'input_history':
                return $this->exportInputHistory($request);
                break;
            case 'groups':
                return $this->exportGroups($request);
                break;
            case 'binnacle_territories':
                return $this->exportGroups($request);
                break;
            case 'revisions':
                return $this->exportRevisions($request);
                break;
            case 'permissions':
                return $this->exportPermissions($request);
                break;
            default:
                return 0;
                break;
        }
    }

    public function exportDemo() {
        PedagogicalsExportJob::dispatch()->onQueue('pedagogicals');
        $result = Artisan::call('queue:work', [
            '--queue' => 'pedagogicals',
            '--timeout' => '1200000'
        ]);
    }

    public function exportPermissions($request) {
        try {

            return Excel::download(new PermissionExport(), "$request->type_excel.xlsx");

        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exportBinnacleImpacts($request)
    {
        try {
            // Rutas donde se encuentran los archivos Excel
            $pathFile1 = storage_path("app/public/export/binnacleImpacts.xlsx");
            $pathFile2 = storage_path("app/public/export/culturalSeedbedBeneficiary.xlsx");
            $pathFile3 = storage_path("app/public/export/beneficiaryNotAssociates.xlsx");

            // Ruta donde queda el .zip
            $pathZip = storage_path("app/public/export/zips/binnacleImpacts.zip");

            // Crear un objeto ZipArchive
            $zip = new ZipArchive();

            // Abrir el archivo ZIP en modo escritura
            if ($zip->open($pathZip, ZipArchive::CREATE) === true) {

                // Agregar los tres archivos Excel al ZIP
                $zip->addFile($pathFile1, 'Bitácoras de impacto.xlsx');
                $zip->addFile($pathFile2, 'Bitácora Semillero Cultural.xlsx');
                $zip->addFile($pathFile3, 'Beneficiarios no impactados.xlsx');

                // Cerrar el archivo ZIP
                $zip->close();

                // Nombre con el que se descargará el archivo ZIP
                $zipFileName = 'binnacleImpacts.zip';
                // return response()->download($pathZip, $zipFileName);
                $headers = [
                    'Content-Type' => 'application/zip',
                ];
                return Response::download($pathZip, $zipFileName, $headers);
            } else {
                throw new \Exception('Error al abrir el archivo ZIP en modo escritura. Ruta: ' . $pathZip . '. Código de error: ' . $zip->status);
            }
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ' Archivo ' . $ex->getFile() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exportJobExcel($request){
        $pathFile = storage_path('app/public/export/' . $request->type_excel . ".xlsx");
        $response = new BinaryFileResponse($pathFile);
        $response->setContentDisposition('attachment', 'file.xls');
        return $response;
    }

    public function exportPecs($request)
    {
        try {

            if (!$request->data) {
                $filename = 'pecs_' . date('d_m_Y_H_i_s') . '.xlsx';

                return Excel::download(new PecsExport($request), $filename);
            }
            $query =  Pec::query();
            $pecs = Pec::get();
            if ($request->status) {
                $pecs = $query->where('status', $request->status)->get();
            }
            if ($request->nac_id) {
                $pecs = $query->where('nac_id', $request->nac_id)->get();
            }
            if ($request->date_start && !$request->date_end) {
                $pecs =  $query->where('activity_date', $request->date_start)->get();
            }
            if ($request->user_id) {
                $pecs = $query->where('created_by', $request->user_id)->get();
            }
            if ($request->date_start && $request->date_end) {
                $pecs = $query->where('activity_date', '>=', $request->date_start)->where('activity_date', '<=', $request->date_end)->get();
            }
            if ($request->nac_id && $request->date_start && $request->date_end) {
                $pecs = $query->where('nac_id', $request->nac_id)->where('activity_date', '>=', $request->date_start)->where('activity_date', '<=', $request->date_end)->get();
            }
            if ($request->date_start &&  $request->date_end && $request->user_id) {
                $pecs = $query->where('activity_date', '>=', $request->date_start)->where('activity_date', '<=', $request->date_end)->where('created_by', $request->user_id)->get();
            }
            if ($request->nac_id && $request->user_id) {
                $pecs = $query->where('nac_id', $request->nac_id)->where('created_by', $request->user_id)->get();
            }
            if ($request->nac_id && $request->date_start && $request->date_end && $request->status) {
                $pecs = $query->where('nac_id', $request->nac_id)->where('activity_date', '>=', $request->date_start)->where('activity_date', '<=', $request->date_end)->where('status', $request->status)->get();
            }
            if ($request->nac_id && $request->date_start && $request->date_end && $request->status && $request->user_id) {
                $pecs = $query->where('nac_id', $request->nac_id)->where('activity_date', '>=', $request->date_start)->where('activity_date', '<=', $request->date_end)->where('status', $request->status)->get();
            }
            return  $pecs->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exportRevisions($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new RevisionExportMultipleSheets($request), "$request->type_excel.xlsx");
            }
            return 0;
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exportVariable($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new VariablesExport, "$request->type_excel.xlsx");
            }
            return 0;
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exporteAmbassador($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new AmbassadorExport($request), "$request->type_excel.xlsx");
            }
            return 0;
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exporteManagerSupervisionvisit($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new ManagerSupervisionvisitsExport($request), "$request->type_excel.xlsx");
            }
            return 0;
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exporteMonitoringReport($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new MonitoringReportExport($request), "$request->type_excel.xlsx");
            }
            return 0;
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exporteMethodologicalStrengthening($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new MethodologicalStrengtheningExport($request), "$request->type_excel.xlsx");
            }
            return 0;
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exporteBinnacleTerritorie($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new BinnacleTerritorieExport($request), "$request->type_excel.xlsx");
            }
            return 0;
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exportLoginAccess($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new LoginAccessExport($request), "$request->type_excel.xlsx");
            }
            $query = AccessLogin::query();
            $accessLogins = AccessLogin::get();

            if ($request->date_start) {
                $accessLogins =  $query->where('date', $request->date_start)->get();
            }
            if ($request->date_start && $request->date_end) {
                $accessLogins =  $query->where('date', '>=', $request->date_start)->where('date', '<=', $request->date_end)->get();
            }
            return  $accessLogins->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine() . $ex->getMessage(), 'success' => false], 404);
        }
    }
    public function exportUsers($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new UsersExport($request), "$request->type_excel.xlsx");
            }
            return User::count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
    public function exportPolls($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new PollsExport($request), "$request->type_excel.xlsx");
            }
            $query = Poll::query();
            $polls = Poll::get();

            if ($request->status) {
                $polls = $query->where('status', $request->status)->get();
            }
            if ($request->date_start) {
                $polls =  $query->whereDate('created_at', $request->date_start)->get();
            }
            if ($request->user_id) {
                $polls = $query->where('user_id', $request->user_id)->get();
            }
            if ($request->date_start && $request->date_end) {
                $polls = $query->whereDate('created_at', '>=', $request->date_start)->whereDate('created_at', '<=', $request->date_end)->get();
            }
            if ($request->date_start &&  $request->date_end && $request->user_id) {
                $polls = $query->whereDate('created_at', '>=', $request->date_start)->whereDate('created_at', '<=', $request->date_end)->where('user_id', $request->user_id)->get();
            }
            if ($request->date_start && $request->date_end && $request->status) {
                $polls = $query->whereDate('created_at', '>=', $request->date_start)->whereDate('created_at', '<=', $request->date_end)->where('status', $request->status)->get();
            }
            if ($request->date_start && $request->date_end && $request->status && $request->user_id) {
                $polls = $query->whereDate('created_at', '>=', $request->date_start)->whereDate('created_at', '<=', $request->date_end)->where('status', $request->status)->get();
            }
            return $polls->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
    public function exportPollDesertions($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new PollDesertionsExport($request), "$request->type_excel.xlsx");
            }
            $query =  PollDesertion::query();
            $pollDesertions = PollDesertion::get();

            if ($request->status) {
                $pollDesertions = $query->where('status', $request->status)->get();
            }
            if ($request->nac_id) {
                $pollDesertions = $query->where('nac_id', $request->nac_id)->get();
            }
            if ($request->date_start) {
                $pollDesertions =  $query->where('date', $request->date_start)->get();
            }
            if ($request->user_id) {
                $pollDesertions = $query->where('user_id', $request->user_id)->get();
            }
            if ($request->date_start && $request->date_end) {
                $pollDesertions = $query->where('date', '>=', $request->date_start)->where('date', '<=', $request->date_end)->get();
            }
            if ($request->nac_id && $request->date_start && $request->date_end) {
                $pollDesertions = $query->where('nac_id', $request->nac_id)->where('date', '>=', $request->date_start)->where('date', '<=', $request->date_end)->get();
            }
            if ($request->date_start &&  $request->date_end && $request->user_id) {
                $pollDesertions = $query->where('date', '>=', $request->date_start)->where('date', '<=', $request->date_end)->where('user_id', $request->user_id)->get();
            }
            if ($request->nac_id && $request->user_id) {
                $pollDesertions = $query->where('nac_id', $request->nac_id)->where('user_id', $request->user_id)->get();
            }
            if ($request->nac_id && $request->date_start && $request->date_end && $request->status) {
                $pollDesertions = $query->where('nac_id', $request->nac_id)->where('date', '>=', $request->date_start)->where('date', '<=', $request->date_end)->where('status', $request->status)->get();
            }
            if ($request->nac_id && $request->date_start && $request->date_end && $request->status && $request->user_id) {
                $pollDesertions = $query->where('nac_id', $request->nac_id)->where('date', '>=', $request->date_start)->where('date', '<=', $request->date_end)->where('status', $request->status)->get();
            }

            return  $pollDesertions->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exportBeneficiaries($request, $who)
    {
        try {
            if (!$request->data) {
                return Excel::download(new BeneficiariesExport($request, $who), "$request->type_excel.xlsx");
            }
            $query =  Beneficiary::query();
            $beneficiaries = Beneficiary::get();
            if ($request->status) {
                $beneficiaries = $query->where('status',  $request->status)->get();
            }
            if ($request->nac_id) {
                $beneficiaries = $query->where('nac_id',  $request->nac_id)->get();
            }
            if ($request->date_start) {
                $beneficiaries =  $query->whereDate('created_at',  $request->date_start)->get();
            }
            if ($request->user_id) {
                $beneficiaries = $query->where('created_by',  $request->user_id)->get();
            }
            if ($request->date_start &&  $request->date_end) {
                $beneficiaries = $query->whereDate('created_at', '>=',  $request->date_start)->whereDate('created_at', '<=',  $request->date_end)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
                $beneficiaries = $query->where('nac_id',  $request->nac_id)->whereDate('created_at', '>=',  $request->date_start)->whereDate('created_at', '<=',  $request->date_end)->get();
            }
            if ($request->date_start &&   $request->date_end &&  $request->user_id) {
                $beneficiaries = $query->whereDate('created_at', '>=',  $request->date_start)->whereDate('created_at', '<=',  $request->date_end)->where('created_by',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->user_id) {
                $beneficiaries = $query->where('nac_id',  $request->nac_id)->where('created_by',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
                $beneficiaries = $query->where('nac_id',  $request->nac_id)->whereDate('created_at', '>=',  $request->date_start)->whereDate('created_at', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
                $beneficiaries = $query->where('nac_id',  $request->nac_id)->whereDate('created_at', '>=',  $request->date_start)->whereDate('created_at', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }

            return $beneficiaries->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exportAttendats($request)
    {
        try {

            if (!$request->data) {
                return Excel::download(new AttendantsExport($request), "$request->type_excel.xlsx");
            }
            return 0;
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
    public function exportParentschools($request)
    {
        try {

            if (!$request->data) {
                return Excel::download(new ParentSchoolsExport($request), "$request->type_excel.xlsx");
            }
            $query =  ParentSchool::query();
            $parentSchools = ParentSchool::get();
            if ($request->status) {
                $parentSchools = $query->where('status',  $request->status)->get();
            }
            if ($request->nac_id) {
                $parentSchools = $query->where('nac_id',  $request->nac_id)->get();
            }
            if ($request->date_start) {
                $parentSchools =  $query->where('date',  $request->date_start)->get();
            }
            if ($request->user_id) {
                $parentSchools = $query->where('created_by',  $request->user_id)->get();
            }
            if ($request->date_start &&  $request->date_end) {
                $parentSchools = $query->where('date', '>=',  $request->date_start)->where('date', '<=',  $request->date_end)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
                $parentSchools = $query->where('nac_id',  $request->nac_id)->where('date', '>=',  $request->date_start)->where('date', '<=',  $request->date_end)->get();
            }
            if ($request->date_start &&   $request->date_end &&  $request->user_id) {
                $parentSchools = $query->where('date', '>=',  $request->date_start)->where('date', '<=',  $request->date_end)->where('created_by',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->user_id) {
                $parentSchools = $query->where('nac_id',  $request->nac_id)->where('created_by',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
                $parentSchools = $query->where('nac_id',  $request->nac_id)->where('date', '>=',  $request->date_start)->where('date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
                $parentSchools = $query->where('nac_id',  $request->nac_id)->where('date', '>=',  $request->date_start)->where('date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            return $parentSchools->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exportMethodologicalSheetsOne($request)
    {
        if (!$request->data) {
            return Excel::download(new MethodologicalSheetsOneExport($request), "$request->type_excel.xlsx");
        }
        $query =  MethodologicalSheetsOne::query();
        $methodologicalSheetsOnes = MethodologicalSheetsOne::get();
        if ($request->status) {
            $methodologicalSheetsOnes = $query->where('status',  $request->status)->get();
        }
        if ($request->nac_id) {
            $methodologicalSheetsOnes = $query->where('nac_id',  $request->nac_id)->get();
        }
        if ($request->date_start) {
            $methodologicalSheetsOnes =  $query->where('activity_date',  $request->date_start)->get();
        }
        if ($request->user_id) {
            $methodologicalSheetsOnes = $query->where('user_id',  $request->user_id)->get();
        }
        if ($request->date_start &&  $request->date_end) {
            $methodologicalSheetsOnes = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
            $methodologicalSheetsOnes = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->date_start &&   $request->date_end &&  $request->user_id) {
            $methodologicalSheetsOnes = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->user_id) {
            $methodologicalSheetsOnes = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
            $methodologicalSheetsOnes = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
            $methodologicalSheetsOnes = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        return $methodologicalSheetsOnes->count();
    }

    public function exportMethodologicalSheetsTwo($request)
    {
        if (!$request->data) {
            return Excel::download(new MethodologicalSheetsTwoExport($request), "$request->type_excel.xlsx");
        }
        $query =  MethodologicalSheetsTwo::query();
        $methodologicalSheetsTwos = MethodologicalSheetsTwo::get();
        if ($request->status) {
            $methodologicalSheetsTwos = $query->where('status',  $request->status)->get();
        }
        if ($request->nac_id) {
            $methodologicalSheetsTwos = $query->where('nac_id',  $request->nac_id)->get();
        }
        if ($request->date_start) {
            $methodologicalSheetsTwos =  $query->where('activity_date',  $request->date_start)->get();
        }
        if ($request->user_id) {
            $methodologicalSheetsTwos = $query->where('user_id',  $request->user_id)->get();
        }
        if ($request->date_start &&  $request->date_end) {
            $methodologicalSheetsTwos = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
            $methodologicalSheetsTwos = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->date_start &&   $request->date_end &&  $request->user_id) {
            $methodologicalSheetsTwos = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->user_id) {
            $methodologicalSheetsTwos = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
            $methodologicalSheetsTwos = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
            $methodologicalSheetsTwos = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        return $methodologicalSheetsTwos->count();
    }

    public function exportCulturalEnsemble($request)
    {
        if (!$request->data) {
            return Excel::download(new CulturalEnsembleExport($request), "$request->type_excel.xlsx");
        }
        $query =  CulturalEnsemble::query();
        $culturalEnsembles = CulturalEnsemble::get();
        if ($request->status) {
            $culturalEnsembles = $query->where('status',  $request->status)->get();
        }
        if ($request->nac_id) {
            $culturalEnsembles = $query->where('nac_id',  $request->nac_id)->get();
        }
        if ($request->date_start) {
            $culturalEnsembles =  $query->where('activity_date',  $request->date_start)->get();
        }
        if ($request->user_id) {
            $culturalEnsembles = $query->where('user_id',  $request->user_id)->get();
        }
        if ($request->date_start &&  $request->date_end) {
            $culturalEnsembles = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
            $culturalEnsembles = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->date_start &&   $request->date_end &&  $request->user_id) {
            $culturalEnsembles = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->user_id) {
            $culturalEnsembles = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
            $culturalEnsembles = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
            $culturalEnsembles = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        return $culturalEnsembles->count();
    }

    public function exporteCulturalCirculation($request)
    {
        if (!$request->data) {
            return Excel::download(new CulturalCirculationExport($request), "$request->type_excel.xlsx");
        }
        $query =  CulturalCirculation::query();
        $culturalCirculations = CulturalCirculation::get();
        if ($request->status) {
            $culturalCirculations = $query->where('status',  $request->status)->get();
        }
        if ($request->nac_id) {
            $culturalCirculations = $query->where('nac_id',  $request->nac_id)->get();
        }
        if ($request->date_start) {
            $culturalCirculations =  $query->where('activity_date',  $request->date_start)->get();
        }
        if ($request->user_id) {
            $culturalCirculations = $query->where('user_id',  $request->user_id)->get();
        }
        if ($request->date_start &&  $request->date_end) {
            $culturalCirculations = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
            $culturalCirculations = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->date_start &&   $request->date_end &&  $request->user_id) {
            $culturalCirculations = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->user_id) {
            $culturalCirculations = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
            $culturalCirculations = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
            $culturalCirculations = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        return $culturalCirculations->count();
    }

    public function exporteCulturalSeedbed($request)
    {
        if (!$request->data) {
            return Excel::download(new CulturalSeedBedExport($request), "$request->type_excel.xlsx");
        }
        $query =  CulturalSeedbed::query();
        $culturalSeedbeds = CulturalSeedbed::get();
        if ($request->status) {
            $culturalSeedbeds = $query->where('status',  $request->status)->get();
        }
        if ($request->nac_id) {
            $culturalSeedbeds = $query->where('nac_id',  $request->nac_id)->get();
        }
        if ($request->date_start) {
            $culturalSeedbeds =  $query->where('activity_date',  $request->date_start)->get();
        }
        if ($request->user_id) {
            $culturalSeedbeds = $query->where('user_id',  $request->user_id)->get();
        }
        if ($request->date_start &&  $request->date_end) {
            $culturalSeedbeds = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
            $culturalSeedbeds = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->date_start &&   $request->date_end &&  $request->user_id) {
            $culturalSeedbeds = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->user_id) {
            $culturalSeedbeds = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
            $culturalSeedbeds = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
            $culturalSeedbeds = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        return $culturalSeedbeds->count();
    }

    public function exportDialogueTables($request)
    {
        if (!$request->data) {
            return Excel::download(new DialogueTablesExport($request), "$request->type_excel.xlsx");
        }
        $query =  DialogueTable::query();
        $dialogueTables = DialogueTable::get();
        if ($request->status) {
            $dialogueTables = $query->where('status',  $request->status)->get();
        }
        if ($request->nac_id) {
            $dialogueTables = $query->where('nac_id',  $request->nac_id)->get();
        }
        if ($request->date_start) {
            $dialogueTables =  $query->where('activity_date',  $request->date_start)->get();
        }
        if ($request->user_id) {
            $dialogueTables = $query->where('user_id',  $request->user_id)->get();
        }
        if ($request->date_start &&  $request->date_end) {
            $dialogueTables = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
            $dialogueTables = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->date_start &&   $request->date_end &&  $request->user_id) {
            $dialogueTables = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->user_id) {
            $dialogueTables = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
            $dialogueTables = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
            $dialogueTables = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        return $dialogueTables->count();
    }

    public function exportBinnacleMonitors($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new BinnaclePactMonitorsExport($request), "$request->type_excel.xlsx");
            }
            $query =  Binnacle::query();
            $binnacles = Binnacle::get();
            if ($request->status) {
                $binnacles = $query->where('status',  $request->status)->get();
            }
            if ($request->nac_id) {
                $binnacles = $query->where('nac_id',  $request->nac_id)->get();
            }
            if ($request->date_start) {
                $binnacles =  $query->where('activity_date',  $request->date_start)->get();
            }
            if ($request->user_id) {
                $binnacles = $query->where('created_by',  $request->user_id)->get();
            }
            if ($request->date_start &&  $request->date_end) {
                $binnacles = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
                $binnacles = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
            }
            if ($request->date_start &&   $request->date_end &&  $request->user_id) {
                $binnacles = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('created_by',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->user_id) {
                $binnacles = $query->where('nac_id',  $request->nac_id)->where('created_by',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
                $binnacles = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
                $binnacles = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }

            return $binnacles->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ' Archivo ' . $ex->getFile() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
    public function exportManagerMonitorings($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new ManagerMonitoringsExport($request), "$request->type_excel.xlsx");
            }
            $query = ManagerMonitoring::query();
            $managerMonitorings = ManagerMonitoring::get();
            if ($request->status) {
                $managerMonitorings = $query->where('status',  $request->status)->get();
            }
            if ($request->nac_id) {
                $managerMonitorings = $query->where('nac_id',  $request->nac_id)->get();
            }
            if ($request->date_start) {
                $managerMonitorings =  $query->where('activity_date',  $request->date_start)->get();
            }
            if ($request->user_id) {
                $managerMonitorings = $query->where('user_id',  $request->user_id)->get();
            }
            if ($request->date_start &&  $request->date_end) {
                $managerMonitorings = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
                $managerMonitorings = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
            }
            if ($request->date_start &&   $request->date_end &&  $request->user_id) {
                $managerMonitorings = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->user_id) {
                $managerMonitorings = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
                $managerMonitorings = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
                $managerMonitorings = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }

            return $managerMonitorings->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ' Archivo ' . $ex->getFile() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exportMethodologicalInstructionModels($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new MethodologicalInstructionModelsExport($request), "$request->type_excel.xlsx");
            }
            $query =  MethodologicalInstructionModel::query();
            $methodologicalInstructionModels = MethodologicalInstructionModel::get();
            if ($request->status) {
                $methodologicalInstructionModels = $query->where('status',  $request->status)->get();
            }
            if ($request->nac_id) {
                $methodologicalInstructionModels = $query->where('nac_id',  $request->nac_id)->get();
            }
            if ($request->date_start) {
                $methodologicalInstructionModels =  $query->where('activity_date',  $request->date_start)->get();
            }
            if ($request->user_id) {
                $methodologicalInstructionModels = $query->where('created_by',  $request->user_id)->get();
            }
            if ($request->date_start &&  $request->date_end) {
                $methodologicalInstructionModels = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
                $methodologicalInstructionModels = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
            }
            if ($request->date_start &&   $request->date_end &&  $request->user_id) {
                $methodologicalInstructionModels = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('created_by',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->user_id) {
                $methodologicalInstructionModels = $query->where('nac_id',  $request->nac_id)->where('created_by',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
                $methodologicalInstructionModels = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
                $methodologicalInstructionModels = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }

            return $methodologicalInstructionModels->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ' Archivo ' . $ex->getFile() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
    public function exportBinnacleManagers($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new BinnacleManagersExport($request), "$request->type_excel.xlsx");
            }
            $query =  Binnacle::query();
            $binnacles = Binnacle::get();
            if ($request->status) {
                $binnacles = $query->where('status',  $request->status)->get();
            }
            if ($request->nac_id) {
                $binnacles = $query->where('nac_id',  $request->nac_id)->get();
            }
            if ($request->date_start) {
                $binnacles =  $query->where('activity_date',  $request->date_start)->get();
            }
            if ($request->user_id) {
                $binnacles = $query->where('created_by',  $request->user_id)->get();
            }
            if ($request->date_start &&  $request->date_end) {
                $binnacles = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
                $binnacles = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
            }
            if ($request->date_start &&   $request->date_end &&  $request->user_id) {
                $binnacles = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('created_by',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->user_id) {
                $binnacles = $query->where('nac_id',  $request->nac_id)->where('created_by',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
                $binnacles = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
                $binnacles = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            return $binnacles->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ' Archivo ' . $ex->getFile() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
    public function exportPsychosocialInstructions($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new PsychosocialInstructionsExport($request), "$request->type_excel.xlsx");
            }
            $query =  PsychosocialInstruction::query();
            $psychosocialInstructions = PsychosocialInstruction::get();
            if ($request->status) {
                $psychosocialInstructions = $query->where('status',  $request->status)->get();
            }
            if ($request->nac_id) {
                $psychosocialInstructions = $query->where('nac_id',  $request->nac_id)->get();
            }
            if ($request->date_start) {
                $psychosocialInstructions =  $query->where('activity_date',  $request->date_start)->get();
            }
            if ($request->user_id) {
                $psychosocialInstructions = $query->where('user_id',  $request->user_id)->get();
            }
            if ($request->date_start &&  $request->date_end) {
                $psychosocialInstructions = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
                $psychosocialInstructions = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
            }
            if ($request->date_start &&   $request->date_end &&  $request->user_id) {
                $psychosocialInstructions = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->user_id) {
                $psychosocialInstructions = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
                $psychosocialInstructions = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
                $psychosocialInstructions = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }

            return $psychosocialInstructions->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ' Archivo ' . $ex->getFile() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
    public function exportInscriptions($request)
    {
        try {
            if (!$request->data) {
                return Excel::download(new InscriptionExport($request), "Inscripción.xlsx");
            }
            $query =  Inscription::query();
            $inscriptions = Inscription::get();
            if ($request->status) {
                $inscriptions =   $query->where('status', $request->status)->get();
            }
            if ($request->user_id) {
                $inscriptions =   $query->where('created_by', $request->user_id)->get();
            }
            if ($request->nac_id) {
                $inscriptions =  $query->whereHas('benefiary', function ($beneficiary) use ($request) {
                    $beneficiary->where('nac_id', $request->nac_id);
                })->get();
            }
            if ($request->nac_id && $request->status && $request->user_id) {
                $inscriptions =  $query->whereHas('benefiary', function ($beneficiary) use ($request) {
                    $beneficiary->where('nac_id', $request->nac_id);
                })->where('status', $request->status)
                    ->where('created_by', $request->user_id)
                    ->get();
            }

            return $inscriptions->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ' Archivo ' . $ex->getFile() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
    public function exportPsychopedagogicalLogBooks($request)
    {
        try {

            if (!$request->data) {
                return Excel::download(new PsychopedagogicalLogBooksExport($request), "$request->type_excel.xlsx");
            }
            $query =  PsychopedagogicalLogbook::query();
            $psychopedagogicalLogBooks = PsychopedagogicalLogBook::get();

            if ($request->status) {
                $psychopedagogicalLogBooks = $query->where('status',  $request->status)->get();
            }
            if ($request->nac_id) {
                $psychopedagogicalLogBooks = $query->where('nac_id',  $request->nac_id)->get();
            }
            if ($request->date_start) {
                $psychopedagogicalLogBooks =  $query->where('date',  $request->date_start)->get();
            }
            if ($request->user_id) {
                $psychopedagogicalLogBooks = $query->where('user_id',  $request->user_id)->get();
            }
            if ($request->date_start &&  $request->date_end) {
                $psychopedagogicalLogBooks = $query->where('date', '>=',  $request->date_start)->where('date', '<=',  $request->date_end)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
                $psychopedagogicalLogBooks = $query->where('nac_id',  $request->nac_id)->where('date', '>=',  $request->date_start)->where('date', '<=',  $request->date_end)->get();
            }
            if ($request->date_start &&   $request->date_end &&  $request->user_id) {
                $psychopedagogicalLogBooks = $query->where('date', '>=',  $request->date_start)->where('date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->user_id) {
                $psychopedagogicalLogBooks = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
                $psychopedagogicalLogBooks = $query->where('nac_id',  $request->nac_id)->where('date', '>=',  $request->date_start)->where('date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
                $psychopedagogicalLogBooks = $query->where('nac_id',  $request->nac_id)->where('date', '>=',  $request->date_start)->where('date', '<=',  $request->date_end)->where('status',  $request->status)->get();
            }
            return $psychopedagogicalLogBooks->count();
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato ' . $ex->getMessage() . ' Archivo ' . $ex->getFile() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function exportMethodologicalMonitorings($request)
    {
        if (!$request->data) {
            return Excel::download(new MethodologicalMonitoringExport($request), "$request->type_excel.xlsx");
        }
        $query =  MethodologicalMonitoring::query();
        $methodologicalMonitorings = MethodologicalMonitoring::get();
        if ($request->status) {
            $methodologicalMonitorings = $query->where('status',  $request->status)->get();
        }
        if ($request->nac_id) {
            $methodologicalMonitorings = $query->where('nac_id',  $request->nac_id)->get();
        }
        if ($request->date_start) {
            $methodologicalMonitorings =  $query->where('activity_date',  $request->date_start)->get();
        }
        if ($request->user_id) {
            $methodologicalMonitorings = $query->where('user_id',  $request->user_id)->get();
        }
        if ($request->date_start &&  $request->date_end) {
            $methodologicalMonitorings = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
            $methodologicalMonitorings = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->date_start &&   $request->date_end &&  $request->user_id) {
            $methodologicalMonitorings = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->user_id) {
            $methodologicalMonitorings = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
            $methodologicalMonitorings = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
            $methodologicalMonitorings = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        return $methodologicalMonitorings->count();
    }

    public function exportMethodologicalAccompaniments($request)
    {
        if (!$request->data) {
            return Excel::download(new MethodologicalAccompanimentReport($request), "$request->type_excel.xlsx");
        }
        $query =  MethodologicalAccompaniment::query();
        $methodologicalAccompaniments = MethodologicalAccompaniment::get();
        if ($request->status) {
            $methodologicalAccompaniments = $query->where('status',  $request->status)->get();
        }
        if ($request->nac_id) {
            $methodologicalAccompaniments = $query->where('nac_id',  $request->nac_id)->get();
        }
        if ($request->date_start) {
            $methodologicalAccompaniments =  $query->where('activity_date',  $request->date_start)->get();
        }
        if ($request->user_id) {
            $methodologicalAccompaniments = $query->where('user_id',  $request->user_id)->get();
        }
        if ($request->date_start &&  $request->date_end) {
            $methodologicalAccompaniments = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
            $methodologicalAccompaniments = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->date_start &&   $request->date_end &&  $request->user_id) {
            $methodologicalAccompaniments = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->user_id) {
            $methodologicalAccompaniments = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
            $methodologicalAccompaniments = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
            $methodologicalAccompaniments = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        return $methodologicalAccompaniments->count();
    }

    public function exportStrengtheningSuperMonIns($request)
    {
        if (!$request->data) {
            return Excel::download(new StrengtheningSuperMonInsReport($request), "$request->type_excel.xlsx");
        }
        $query =  StrengtheningSupervisionMonitorsInstructors::query();
        $strengtheningSupervisionMonitorsInstructors = StrengtheningSupervisionMonitorsInstructors::get();
        if ($request->status) {
            $strengtheningSupervisionMonitorsInstructors = $query->where('status',  $request->status)->get();
        }
        if ($request->nac_id) {
            $strengtheningSupervisionMonitorsInstructors = $query->where('nac_id',  $request->nac_id)->get();
        }
        if ($request->date_start) {
            $strengtheningSupervisionMonitorsInstructors =  $query->where('activity_date',  $request->date_start)->get();
        }
        if ($request->user_id) {
            $strengtheningSupervisionMonitorsInstructors = $query->where('user_id',  $request->user_id)->get();
        }
        if ($request->date_start &&  $request->date_end) {
            $strengtheningSupervisionMonitorsInstructors = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
            $strengtheningSupervisionMonitorsInstructors = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->date_start &&   $request->date_end &&  $request->user_id) {
            $strengtheningSupervisionMonitorsInstructors = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->user_id) {
            $strengtheningSupervisionMonitorsInstructors = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
            $strengtheningSupervisionMonitorsInstructors = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
            $strengtheningSupervisionMonitorsInstructors = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        return $strengtheningSupervisionMonitorsInstructors->count();
    }

    public function exportStrengtheningOfMonitorings($request)
    {
        if (!$request->data) {
            return Excel::download(new StrengtheningOfMonitoringReport($request), "$request->type_excel.xlsx");
        }
        $query =  StrengtheningOfMonitoring::query();
        $StrengtheningOfMonitorings = StrengtheningOfMonitoring::get();
        if ($request->status) {
            $StrengtheningOfMonitorings = $query->where('status',  $request->status)->get();
        }
        if ($request->nac_id) {
            $StrengtheningOfMonitorings = $query->where('nac_id',  $request->nac_id)->get();
        }
        if ($request->date_start) {
            $StrengtheningOfMonitorings =  $query->where('activity_date',  $request->date_start)->get();
        }
        if ($request->user_id) {
            $StrengtheningOfMonitorings = $query->where('user_id',  $request->user_id)->get();
        }
        if ($request->date_start &&  $request->date_end) {
            $StrengtheningOfMonitorings = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end) {
            $StrengtheningOfMonitorings = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->get();
        }
        if ($request->date_start &&   $request->date_end &&  $request->user_id) {
            $StrengtheningOfMonitorings = $query->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->user_id) {
            $StrengtheningOfMonitorings = $query->where('nac_id',  $request->nac_id)->where('user_id',  $request->user_id)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status) {
            $StrengtheningOfMonitorings = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        if ($request->nac_id &&  $request->date_start &&  $request->date_end &&  $request->status &&  $request->user_id) {
            $StrengtheningOfMonitorings = $query->where('nac_id',  $request->nac_id)->where('activity_date', '>=',  $request->date_start)->where('activity_date', '<=',  $request->date_end)->where('status',  $request->status)->get();
        }
        return $StrengtheningOfMonitorings->count();
    }

    public function exportGroups($request)
    {
        if (!$request->data) {
            return Excel::download(new GroupExport($request), "$request->type_excel.xlsx");
        }
        $groups = Group::get();
        return $groups->count();
    }

    public function exportInputHistory($request)
    {
        try {
            if (!$request->data) {
                $filename = 'Historial_de_Entradas_' . date('d_m_Y_H_i_s') . '.xlsx';
                return Excel::download(new InputHistory($request), $filename);
            }
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
}
