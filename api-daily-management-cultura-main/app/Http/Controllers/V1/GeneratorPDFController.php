<?php

namespace App\Http\Controllers\V1;

use App\Models\PsychopedagogicalLogbooks\PsychopedagogicalLogbook;
use App\Models\PsychosocialInstructions\PsychosocialInstruction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Models\MethodologicalInstructionModel;
use App\Models\DialogueTables\DialogueTable;
use App\Models\ParentSchools\ParentSchool;
use App\Models\Inscriptions\Inscription;
use App\Http\Controllers\Controller;
use App\Models\ManagerMonitoring;
use Illuminate\Http\Request;
use App\Models\Pedagogical;
use ZipArchive;
use Exception;
use File;
use PDF;

class GeneratorPDFController extends Controller
{
    // Dependiendo del Typo que venga se llama la función acá
    public function generate(Request $request, $id) {

        // Utilizamos solo el tipo de PDF que viene
        $type = trim($request->type, '"');

        // Generamos dependiendo del tipo de PDF que viene
        switch ($type) {
            case "dialogueTables":
                return $this->formatDialogueTables($id);
                break;
            case 'parentschools':
                return $this->formateParentSchools($id);
                break;
            case 'psychopedagogicallogs':
                return $this->formatePsychoPedagogicallogs($id);
                break;
            case 'psychosocialInstructions':
                return $this->formatePsychosocialInstructions($id);
                break;
            case 'pedagogicals':
                return $this->formatePedagogicals($id);
                break;
            case 'methodologicalInstructionModels':
                return $this->formateMethodologicalInstructions($id);
                break;
            case 'manager_monitorings':
                return $this->formateManagerMonitorings($id);
                break;
            case 'inscriptions':
                return $this->formateInscriptionBeneficiaries($id);
                break;
            default:
                return response()->json('Error al generar el PDF', 404);
                break;
        }

    }

    // Funcionando  Boton en Vue
    public function formatDialogueTables($id)
    {
        try {

            $personalizesheet = [
                'margin_top' => '50mm',
                'margin_bottom' => '35mm',
            ];

            $select = [
                'id',
                'activity_date',
                'start_time',
                'final_hour',
                'nac_id',
                'user_review_manager_cultural_id',
                'user_id',
                'target_workday',
                'theme',
                'schedule_day',
                'workday_description',
                'achievements_difficulties',
                'alerts',
                'place_image1',
                'place_image2',
                'consecutive',
                'status'
            ];
            $relaciones = [
                'nac:id,name',
                'assistant:id,assistant_name,assistant_document_number,assistant_position,nac_id,assistant_phone,assistant_email',
                'assistant.nac:id,name',
            ];

            $DialogueTable = DialogueTable::findOrFail($id);

            $titulos = [
                'Titulo1' => 'INFORME MESA DE DIÁLOGO CULTURAL',
                'Titulo2' => '1. DATOS GENERALES',
                'Titulo3' => '2. DESCRIPCIÓN DE LA JORNADA',
                'Titulo4' => '3. REGISTRO FOTOGRÁFICO',
                'Titulo5' => 'PARTICIPANTES',
            ];
            $textoCabezera = [
                'Codigo' => '07-GC-03',
                'Version' => '1',
                'Fecha' => '01 de abril de 2021',
            ];
            $data = [
                'Formatname' =>'Formato_Mesa_Dialogo',
                'Data' => $DialogueTable,
                'Titles' => $titulos,
                'Namepdf' => $DialogueTable->consecutive,
                'Headerpersonalice' => false,
                'Headertext' => $textoCabezera,
                'Display' => 'pdf',
                'Personalizesheet' => $personalizesheet,
                'Cantpdf' => 1,
                'Namefile' => 'Mesa_Dialogo',
            ];

            $pdf = $this->generatePDF($data);

            return $pdf;

        } catch (Exception $e) {
            return response()->json([
                "line" => $e->getLine(),
                "explanation" => $e->getMessage(),
                "message" => "No hay registo o algo salio mal a exportar Mesa de Dialogo"
            ], 400);
        }
    }

    // Funcionando Boton en Vue
    public function formateParentSchools($id){
        try {

            $personalizesheet = [
                'margin_top' => '50mm',
                'margin_bottom' => '30mm',
            ];

            $select = [
                'id',
                'consecutive',
                'date',
                'monitor_id',
                'start_time',
                'final_time',
                'place_attention',
                'contact',
                'objective',
                'development',
                'conclusions',
                'development_activity_image',
                'evidence_participation_image',
                'status'
            ];
            $relaciones = [
                'monitor:id,name',
                'addedWizards:parent_school_id,assistant_id',
                'addedWizards.assistant:id,assistant_name,assistant_document_number,assistant_position,nac_id,assistant_phone,assistant_email',
                'addedWizards.assistant.nac:id,name'
            ];

            $ParentSchools = ParentSchool::findOrFail($id);

            $titulos = [
                'Titulo1' => '1. Datos generales',
                'Titulo2' => '2. Desarollo de la actividad',
                'Titulo3' => '3. Registro fotográfico',
                'Titulo4' => 'Participantes',
            ];
            $textoCabezera = [
                'Codigo' => '16-PS-05',
                'Version' => '1',
                'Fecha' => '16 de agosto de 2022',
            ];
            $data = [
                'Formatname' =>'Formato_Escuela_Padres',
                'Data' => $ParentSchools,
                'Titles' => $titulos,
                'Namepdf' => $ParentSchools->consecutive,
                'Headerpersonalice' => false,
                'Headertext' => $textoCabezera,
                'Display' => 'pdf',
                'Personalizesheet' => $personalizesheet,
                'Cantpdf' => 1,
                'Namefile' => 'Escuela_Padres',
            ];
            $pdf = $this->generatePDF($data);

            return $pdf;
        } catch (Exception $e) {
            return response()->json([
                "line" => $e->getLine(),
                "explanation" => $e->getMessage(),
                "message" => "No hay registo o Algo salio mal a exportar Escuela de padre"
            ], 400);
        }
    }

    // Funcionando Boton en Vue
    public function formatePsychoPedagogicallogs($id){
        try {

            $personalizesheet = [
                'margin_top' => '50mm',
                'margin_bottom' => '30mm',
            ];

            $select = [
                'id', 'consecutive', 'date', 'nac_id', 'start_time', 'final_time',
                'person_served_name', 'monitor_id', 'objective', 'development', 'referrals', 'conclusions_reflections_commitments',
                'alert_reporting_tracking', 'development_activity_image', 'evidence_participation_image', 'status'
            ];
            $relaciones = [
                'addedWizards:id,psychopedagogical_logbook_id,assistant_name,assistant_document_number,assistant_position,assistant_phone,assistant_email,nac_id',
                'assistanceMonitors:id,psychopedagogical_logbook_id,monitor_id',
                'monitor:id,name',
                'addedWizards.nac:id,name'
            ];
            $PsychopedagogicalLogbook = PsychopedagogicalLogbook::findOrFail($id);

            if ($PsychopedagogicalLogbook->count() > 0) {
            $titulos = [
                'Titulo1' => '1. Datos generales',
                'Titulo2' => '2. Descripción de la jornada',
                'Titulo3' => '3. Registro fotográfico',
                'Titulo4' => 'Participantes',
            ];
            $textoCabezera = [
                'Codigo' => '12-PS-04',
                'Version' => '1',
                'Fecha' => '01 de abril de 2021',
            ];
            $data = [
                'Formatname' =>'Formato_Bitacora_Psicopedagogica',
                'Data' => $PsychopedagogicalLogbook,
                'Titles' => $titulos,
                'Namepdf' => $PsychopedagogicalLogbook->consecutive,
                'Headerpersonalice' => false,
                'Headertext' => $textoCabezera,
                'Display' => 'pdf',
                'Personalizesheet' => $personalizesheet,
                'Cantpdf' => 1,
                'Namefile' => 'Bitacora_Psicopedagogica',
            ];
            $pdf = $this->generatePDF($data);

            return $pdf;

            } else {
                return response()->json([
                    'message' => 'No hay Registros de Bitacoras Psicopedagogica',
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                "line" => $e->getLine(),
                "explanation" => $e->getMessage(),
                "message" => "Algo salio mal a exportar Bitacora psicosocial"
            ], 400);
        }
    }

    // Funcionando Boton en Vue
    public function formateInscriptionBeneficiaries($id){
        try {

            $personalizesheet = [
                'margin_top' => '55mm',
                'margin_bottom' => '30mm',
            ];

            $select = [
                'id', 'created_by', 'beneficiary_id', 'consecutive', 'status', 'reject_message', 'user_review_support_follow_id', 'apro_file1', 'apro_file2'
            ];

            $relaciones = [
                'benefiary:id,neighborhood_id,user_id,full_name,institution_entity_referred,accept,linkage_project,participant_type,type_document,document_number,neighborhood_new,zone,stratum,phone,email,file,status,nac_id,created_at',
                'benefiary.attendant:id,beneficiary_id,full_name,type_document,document_number,zone,phone,email,relationship',
                'benefiary.health_data:entity_name_id,health_data_id,health_data_type,medical_service,health_condition',
                'benefiary.nac:id,name',
                'benefiary.health_data.entity_name:id,name',
                'benefiary.neighborhood:id,name',
                'benefiary.attendant.neighborhood:id,name',
                'benefiary.socio_demo:socio_demo_id,age,gender,decision_study,educational_level,decision_disability,disability_type,ethnicity,condition',
                'benefiary.userbeneficiario:id,name',
            ];

            $Inscription = Inscription::findOrFail($id);

            $titulos = [
                'Titulo1' => 'FORMATO DE INSCRIPCIÓN DE BENEFICIARIOS',
                'Titulo2' => '1. INFORMACIÓN DE VINCULACIÓN',
                'Titulo3' => '1. DATOS GENERALES',
                'Titulo4' => '2. DATOS PERSONALES',
                'Titulo5' => '3. CARACTERIZACIÓN SOCIODEMOGRÁFICA',
                'Titulo6' => '4. INFORMACIÓN DE SALUD',
                'Titulo7' => 'FORMATO DE INSCRIPCIÓN DE ACUDIENTES',
                'Titulo8' => '1. DATOS PERSONALES',
                'Titulo9' => '2. CARACTERIZACIÓN SOCIODEMOGRÁFICA',
                'Titulo10' => '3. INFORMACIÓN DE SALUD',
            ];
            $textoCabezera = [
                'Codigo' => '03-MC-03',
                'Version' => '1',
                'Fecha' => '01 de abril de 2021',
            ];

            /* if ($Inscription[$i]->apro_file1 == null ||  $Inscription[$i]->apro_file2 == null) {
                return response()->json([
                    'message' => 'La Inscripcion '.$Inscription[$i]->consecutive. ' No ha subido la validacion del participante y del acudiente',
                ], 400);
            } */
            $data = [
                'Formatname' =>'Formato_Inscripcion_Beneficiarios',
                'Data' => $Inscription,
                'Titles' => $titulos,
                'Namepdf' => $Inscription->consecutive,
                'Headerpersonalice' => false,
                'Headertext' => $textoCabezera,
                'Display' => 'pdf',
                'Personalizesheet' => $personalizesheet,
                'Cantpdf' => 1,
                'Namefile' => 'Inscripcion_Beneficiarios',
            ];

            $pdf = $this->generatePDF($data);

            return $pdf;

        } catch (Exception $e) {
            return response()->json([
                "line" => $e->getLine(),
                "explanation" => $e->getMessage(),
                "message" => "Algo salio mal a exportar Inscripciones"
            ], 400);
        }
    }

    // Funcionando Boton en Vue
    public function formatePsychosocialInstructions($id)
    {
        try {

            $personalizesheet = [
                'margin_top' => '50mm',
                'margin_bottom' => '30mm',
            ];

            $select = [
                'id',
                'user_id',
                'consecutive',
                'activity_date',
                'start_time',
                'final_hour',
                'nac_id',
                'objective_day',
                'themes_day',
                'development_activity_image',
                'evidence_participation_image',
                'development_themes',
                'conclusions_reflections_commitments',
                'report_followup_alerts',
                'status'
            ];
            $relaciones = [
                'nac:id,name',
                'assistants:id,assistant_name,assistant_document_number,assistant_position,assistant_phone,assistant_email,nac_id',
                'assistants.nac:id,name',
                'user:id,name'
            ];

            $PsychosocialInstruction = PsychosocialInstruction::findOrFail($id);

            $titulos = [
                'Titulo1' => '1. Datos generales',
                'Titulo2' => '2. Descripción de la jornada',
                'Titulo3' => '3. Registro fotográfico',
                'Titulo4' => 'Participantes',
            ];
            $textoCabezera = [
                'Codigo' => '09-PS-01',
                'Version' => '1',
                'Fecha' => '01 de abril de 2021',
            ];

            $data = [
                'Formatname' =>'Formato_Instruccion_Psicosocial',
                'Data' => $PsychosocialInstruction,
                'Titles' => $titulos,
                'Namepdf' => $PsychosocialInstruction->consecutive,
                'Headerpersonalice' => false,
                'Headertext' => $textoCabezera,
                'Display' => 'pdf',
                'Personalizesheet' => $personalizesheet,
                'Cantpdf' => 1,
                'Namefile' => 'Instruccion_Psicosocial',
            ];

            $pdf = $this->generatePDF($data);

            return $pdf;

        } catch (Exception $e) {
            return response()->json([
                "line" => $e->getLine(),
                "explanation" => $e->getMessage(),
                "message" => "Algo salio mal a exportar Instruccion Psicosocial"
            ], 400);
        }
    }

    //  Funcionando Boton en Vue
    public function formatePedagogicals($id)
    {
        try {

            $personalizesheet = [
                'margin_top' => '50mm',
                'margin_bottom' => '30mm',
            ];

            $select = [
                'id',
                'consecutive',
                'nac_id',
                'cultural_right_id',
                'expertise_id',
                'orientation_id',
                'activity_name',
                'activity_date',
                'experiential_objective',
                'lineament_id',
                'manifestation',
                'process',
                'product',
                'resources',
                'status',
            ];

            $relaciones = [
                'nac:id,name',
                'cultural_right:id,name',
                'expertise:id,name',
                'orientation:id,name'
            ];

            $Pedagogical = Pedagogical::findOrFail($id);

            $titulos = [
                'Titulo1' => '1. Datos generales',
                'Titulo2' => '2. Componente metodológico',
                'Titulo3' => '3. Etapas y desarrollo de la macroestructura cultural',
            ];
            $textoCabezera = [
                'Codigo' => '02-MC-02',
                'Version' => '1',
                'Fecha' => '01 de abril de 2021',
            ];

            $data = [
                'Formatname' =>'Formato_Fichas_Pedagogicas',
                'Data' => $Pedagogical,
                'Titles' => $titulos,
                'Namepdf' => $Pedagogical->consecutive,
                'Headerpersonalice' => false,
                'Headertext' => $textoCabezera,
                'Display' => 'pdf',
                'Personalizesheet' => $personalizesheet,
                'Cantpdf' => 1,
                'Namefile' => 'Fichas_Pedagogicas',
            ];

            $pdf = $this->generatePDF($data);

            return $pdf;

        } catch (Exception $e) {
            return response()->json([
                "line" => $e->getLine(),
                "explanation" => $e->getMessage(),
                "message" => "Algo salio mal a exportar Fichas Pedagogicas"
            ], 400);
        }
    }

    // Funcionando Boton en Vue
    public function formateMethodologicalInstructions($id)
    {
        try {

            $personalizesheet = [
                'margin_top' => '50mm',
                'margin_bottom' => '30mm',
            ];

            $select = [
                'id',
                'place',
                'activity_date',
                'start_time',
                'final_hour',
                'expertise_id',
                'nac_id',
                'created_by',
                'goals_met',
                'explanation',
                'pedagogical_comments',
                'technical_practical_comments',
                'methodological_comments',
                'others_observations',
                'place_file1',
                'place_file2',
                'consecutive',
                'status',
                'user_method_support_id'
            ];

            $relaciones = [
                'nac:id,name',
                'expertise:id,name',
                'user:id,name',
                'assistants:id,name,email'
            ];

            $MethodologicalInstructionModel = MethodologicalInstructionModel::findOrFail($id);

            $titulos = [
                'Titulo1' => '1. Datos generales',
                'Titulo2' => '2. Descripción de la jornada',
                'Titulo3' => '3. Registro fotográfico',
                'Titulo4' => 'Participantes'
            ];
            $textoCabezera = [
                'Codigo' => '02-MC-02',
                'Version' => '1',
                'Fecha' => '01 de abril de 2021',
            ];

            $data = [
                'Formatname' =>'Formato_Acta_Transferencia_Metodologica',
                'Data' => $MethodologicalInstructionModel,
                'Titles' => $titulos,
                'Namepdf' => $MethodologicalInstructionModel->consecutive,
                'Headerpersonalice' => false,
                'Headertext' => $textoCabezera,
                'Display' => 'pdf',
                'Personalizesheet' => $personalizesheet,
                'Cantpdf' => 1,
                'Namefile' => 'Acta_Transferencia_Metodologica',
            ];

            $pdf = $this->generatePDF($data);

            return $pdf;

        } catch (Exception $e) {
            return response()->json([
                "line" => $e->getLine(),
                "explanation" => $e->getMessage(),
                "message" => "Algo salio mal a exportar Instruccion Metodologica"
            ], 400);
        }
    }

    // Funcionando
    public function formateManagerMonitorings($id)
    {
        try {

            $personalizesheet = [
                'margin_top' => '50mm',
                'margin_bottom' => '25mm',
            ];

            $select = [
                'id',
                'user_id',
                'monitor_id',
                'activity_date',
                'start_time',
                'final_hour',
                'target_tracking',
                'nac_id',
                'cultural_process',
                'cultural_guidelines',
                'cultural_communication',
                'difficulty_cultural_process',
                'proposal_improvement',
                'consecutive',
                'status',
                'user_method_support_id'
            ];

            $relaciones = [
                'nac:id,name',
                'monitor:id,name'
            ];

            $ManagerMonitoring = ManagerMonitoring::findOrFail($id);

            $titulos = [
                'Titulo1' => '1. Datos generales',
                'Titulo2' => '2. Evaluación y retroalimentación metodológica',
            ];
            $textoCabezera = [
                'Codigo' => '08-GC-04',
                'Version' => '1',
                'Fecha' => '01 de abril de 2021',
            ];

            $data = [
                'Formatname' =>'Formato_Seguimiento_Gestor_Cultural',
                'Data' => $ManagerMonitoring,
                'Titles' => $titulos,
                'Namepdf' => $ManagerMonitoring->consecutive,
                'Headerpersonalice' => false,
                'Headertext' => $textoCabezera,
                'Display' => 'pdf',
                'Personalizesheet' => $personalizesheet,
                'Cantpdf' => 1,
                'Namefile' => 'Seguimiento_Gestor_Cultural',
            ];

            return $this->generatePDF($data);

        } catch (Exception $e) {
            return response()->json([
                "line" => $e->getLine(),
                "explanation" => $e->getMessage(),
                "message" => "Algo salio mal a exportar Instruccion Metodologica"
            ], 400);
        }
    }

    // Generar Pdf
    public function generatePDF($data){
        try {
            $imagen = base64_encode(file_get_contents(public_path() . '/img/Reportes/logos.svg'));

            if ($data['Display'] != 'pdf') {
                return View('pdf.' . $data['Formatname'] . '.body', [
                    'titulos' => $data['Titles'],
                    'data' => $data['Data']
                ]);
            }
            $pdf = PDF::loadView('pdf.' . $data['Formatname'] . '.body', [
                'titulos' => $data['Titles'],
                'data' => $data['Data']
            ]);

            $data['Image'] = $imagen;
            $header = $this->headerPdf($data);
            // tamaño de hoja
            $pdf->setOption('margin-top', $data['Personalizesheet']['margin_top']);
            $pdf->setOption('margin-bottom', $data['Personalizesheet']['margin_bottom']);
            $pdf->setOption('header-html', $header);

            if ($data['Namefile'] == 'Inscripcion_Beneficiarios') {
                $path = $this->createFile($data['Namefile'], $data['Data']['benefiary']['full_name']);
            } else {
                $path = $this->createFile($data['Namefile']);
            }

            $nameFile = $data['Namepdf'] . '.pdf';

            // Verificar si el archivo existe
            if (!file_exists(public_path($path) . $nameFile)) {
                $pdf->save(public_path($path) . $nameFile);
            }

            // return response()->download(public_path($path) . $nameFile, $nameFile);


            // $response = new BinaryFileResponse(public_path($path) . $nameFile);
            // $response->setContentDisposition('attachment', 'file.xls');
            // return $response;

            return $path . $nameFile;

        } catch (Exception $e) {
            return response()->json([
                "line" => $e->getLine(),
                "explanation" => $e->getMessage(),
                "message" => "Algo salio mal a generar el pdf"
            ], 400);
        }
    }

    public function headerPdf($data){
        if ($data['Headerpersonalice']) {
            $headerHtml = view('pdf.'.$data['Formatname'].'.header', [
                // 'datos' => $TCabezera,
                'Image' => $data['Image'],
            ]);
        } else {
            $headerHtml = view('pdf.header', [
                'datos' => $data['Headertext'],
                'imagen' => $data['Image'],
            ]);
        }

        return $headerHtml;
    }

    // Crear Carpeta en public
    public function createFile($namefile, $full_name = null){
        $url = '';
        if ($namefile == 'Inscripcion_Beneficiarios') {
            $path = public_path('report/'.$namefile.'/'.str_replace(' ', '_', $full_name).'/');
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $url = 'report/'.$namefile.'/'.str_replace(' ', '_', $full_name).'/';
        }else {
            $path = public_path('report/'.$namefile.'/');
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $url = 'report/'.$namefile.'/';
        }

        return $url;
    }

    // Generar Zip
    public function generateZip($data){
        try {
            $zip = new ZipArchive;
            $ruta = public_path('report/') . substr($data['Formatname'], 8);
            $fileName = substr($data['Formatname'], 8) . '.zip';

            array_map('unlink', glob($ruta . "/*.zip"));
            if (substr($data['Formatname'], 8) == 'Inscripcion_Beneficiarios') {
                $ruta2 = storage_path('app/public/inscriptions/'.$data['Data']['id'].'/');
                $destino = public_path('report/'.$data['Namefile'].'/'.str_replace(' ', '_', $data['Data']['benefiary']['full_name']).'/');
                $archivos= glob($ruta2.'*.*');

                foreach ($archivos as $archivo){
                    $archivo_copiar= str_replace($ruta2, $destino, $archivo);
                    copy($archivo, $archivo_copiar);
                }

                if ($zip->open(public_path('report/' . $fileName), ZipArchive::CREATE) === TRUE) {
                    $pathInfo = pathinfo($ruta);
                    $parentPath = $pathInfo['dirname'];
                    $dirName = $pathInfo['basename'];
                    $zip->addEmptyDir($dirName);

                    if($ruta == $dirName){
                        self::dirToZip($ruta, $zip, 0);
                    }else{
                        self::dirToZip($ruta, $zip, strlen("$parentPath/"));
                    }
                    $zip->close();

                    $this->rmDir_rf(public_path('report/'.$data['Namefile'].'/'.str_replace(' ', '_', $data['Data']['benefiary']['full_name']).'/'));
                    // rmdir(str_replace(' ', '_', $data['Data']['benefiary']['full_name']));
                }
            } else {
                if ($zip->open(public_path('report/' . $fileName), ZipArchive::CREATE) === TRUE) {
                    $files = File::files($ruta);
                    foreach ($files as $key => $value) {
                        $relativeNameInZipFile = basename($value);
                        $zip->addFile($value, $relativeNameInZipFile);
                    }
                    $zip->close();
                }
                array_map('unlink', glob($ruta . "/*.pdf"));
            }

            // $ruta2 = public_path('img/Reportes/') . substr($data['Formatname'], 8);
            // unlink  ($ruta);
            // array_map('unlink', glob($ruta2 . "/*.jpeg"));
            return response()->download(public_path('report/' . $fileName));
        } catch (\Exception $e) {
            return response()->json([
                "line" => $e->getLine(),
                "explanation" => $e->getMessage(),
                "message" => "Algo salio mal a generar el zip"
            ], 400);
        }
    }

    // Generar Zip dentro de carpetas
    private static function dirToZip($folder, &$zipFile, $exclusiveLength){
        $handle = opendir($folder);
        while(FALSE !== $f = readdir($handle)){
            // Check for local/parent path or zipping file itself and skip
            if($f != '.' && $f != '..' && $f != basename(__FILE__)){
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip
                $localPath = substr($filePath, $exclusiveLength);
                if(is_file($filePath)){
                    $zipFile->addFile($filePath, $localPath);
                }elseif(is_dir($filePath)){
                    // Add sub-directory
                    $zipFile->addEmptyDir($localPath);
                    self::dirToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

    // Eliminar Carpeta
    function rmDir_rf($carpeta){
        foreach(glob($carpeta . "/*") as $archivos_carpeta){
            if (is_dir($archivos_carpeta)){
                rmDir_rf($archivos_carpeta);
            } else {
            unlink($archivos_carpeta);
            }
        }
        rmdir($carpeta);
    }

    public function Download($type, $id) {

        $url = public_path().'/report';
        switch ($type) {
            case "dialogueTables":
                $file = $url . 'mesa_dialogo_' . $id . '.pdf';
                break;
            case 'parentschools':
                $file = $url . 'mesa_dialogo_' . $id . '.pdf';
                break;
            case 'psychopedagogicallogs':
                $file = $url . 'mesa_dialogo_' . $id . '.pdf';
                break;
            case 'psychosocialInstructions':
                $file = $url . 'mesa_dialogo_' . $id . '.pdf';
                break;
            case 'pedagogicals':
                $file = $url . 'mesa_dialogo_' . $id . '.pdf';
                break;
            case 'methodologicalInstructionModels':
                $file = $url . 'mesa_dialogo_' . $id . '.pdf';
                break;
            case 'manager_monitorings':
                $file = $url . 'mesa_dialogo_' . $id . '.pdf';
                break;
            default:
                return response()->json('Error al generar el PDF', 404);
                break;
        }
        return response()->download($file);
    }
}
