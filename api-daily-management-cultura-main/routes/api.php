<?php

use App\Http\Controllers\V1\ModuleController;
use App\Http\Controllers\V1\ModuleItemController;
use App\Http\Controllers\V1\PermissionController;
use App\Http\Controllers\V1\PermissionRoleController;
use App\Http\Controllers\V1\RoleController;
use App\Http\Controllers\V1\RoleUserController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\EntityNameController;
use App\Http\Controllers\V1\CulturalRightController;
use App\Http\Controllers\V1\ExpertiseController;
use App\Http\Controllers\V1\NeighborhoodController;
use App\Http\Controllers\V1\OrientationController;
use App\Http\Controllers\V1\AccessController;
use App\Http\Controllers\V1\ActivityLogController;
use App\Http\Controllers\V1\AlertController;
use App\Http\Controllers\V1\AsistantController;
use App\Http\Controllers\V1\BinnacleController;
use App\Http\Controllers\V1\BinnacleManagerController;
use App\Http\Controllers\V1\MonthlyMonitoringReportsController;
use App\Http\Controllers\V1\DialogueTableController;
use App\Http\Controllers\V1\GeneralController;
use App\Http\Controllers\V1\Monitors\PecController;
use App\Http\Controllers\V1\NacController;
use App\Http\Controllers\V1\PedagogicalController;
use App\Http\Controllers\V1\PsychopedagogicalLogBookController;
use App\Http\Controllers\V1\ManagerMonitoringController;
use App\Http\Controllers\V1\MethodologicalInstructionController;
use App\Http\Controllers\V1\ManagementController;
use App\Http\Controllers\V1\Monitors\InscriptionController;
use App\Http\Controllers\V1\PollController;
use App\Http\Controllers\V1\PollDesertionController;
use App\Http\Controllers\V1\ProfileController;
use App\Http\Controllers\V1\MethodologicalSheetsOneController;
use App\Http\Controllers\V1\psychosocial\ParentSchoolController;
use App\Http\Controllers\V1\psychosocial\PsychosocialInstructionController;
use App\Http\Controllers\V1\ReportController;
use App\Http\Controllers\V1\PDFController as PDFController_V1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\BinnacleTerritorieController;
use App\Http\Controllers\V1\CulturalCirculationController;
use App\Http\Controllers\V1\CulturalEmsembleController;
use App\Http\Controllers\V1\CulturalSeedbedController;
use App\Http\Controllers\V1\CulturalShowController;
use App\Http\Controllers\V1\GenerateCutJobController;
use App\Http\Controllers\V1\GeneratorPDFController;
use App\Http\Controllers\V1\GroupController;
use App\Http\Controllers\V1\MethodologicalAccompanimentController;
use App\Http\Controllers\V1\MethodologicalMonitoringController;
use App\Http\Controllers\V1\MethodologicalSheetsTwoController;
use App\Http\Controllers\V1\MethodologicalStrengtheningController;
use App\Http\Controllers\V1\MonitoringReportController;
use App\Http\Controllers\V1\NotificationController;
use App\Http\Controllers\V1\StrengtheningOfMonitoringController;
use App\Http\Controllers\V1\StrengtheningSupervisionManagerController;
use App\Http\Controllers\V1\StrengtheningSupervisionMonitorsInstructorsController;
use App\Models\ActivityLog;
use App\Models\CulturalRight;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('v1')->group(function () {
    Route::get('get-access', [AccessController::class, 'getAccess']);
    Route::post('have-access', [AccessController::class, 'userHaveAccess']);
    Route::get('get-permissions', [AccessController::class, 'getPermissions']);
    Route::get('get-button-boolean-creates', [AccessController::class, 'getButtonBooleanCreates']);

    Route::apiResources([
        'users' => UserController::class,
        'roles' => RoleController::class,
        'permissions' => PermissionController::class,
        'items' => ModuleItemController::class,
        'modules' => ModuleController::class,
        'roleUser' => RoleUserController::class,
        'permissionRole' => PermissionRoleController::class,
        'entities' => EntityNameController::class,
        'cultural-rights' => CulturalRightController::class,
        'expertises' => ExpertiseController::class,
        'neighborhoods' => NeighborhoodController::class,
        'orientations' => OrientationController::class,
        'nacs' => NacController::class,
        'pedagogicals' => PedagogicalController::class,
        'managermonitorings' => ManagerMonitoringController::class,
        'polls' => PollController::class,
        'assistants' => AsistantController::class,
        'pollDesertions' => PollDesertionController::class,
        //parentschool => para actualizar un registro se debe usar el metodo POST con key PUT al final ya que solicita un form-data, igua para restore
        //parentschool ejemplo => 127.0.0.1:8000/api/v1/parentschool/2?_method=PUT 127.0.0.1:8000/api/v1/parentschool/1/restore?_method=PUT
        'parentschools' => ParentSchoolController::class,
        'profiles' => ProfileController::class,
        'methodologicalsheetsone' => MethodologicalSheetsOneController::class,
        // 'psychopedagogicallogs' => PsychopedagogicalLogBookController::class

        //Rutas de nuevos formularios
        //'culturalEnsembles' => CulturalEmsembleController::class, //ENSAMBLE CULTURAL
        //'culturalCirculations' => CulturalCirculationController::class, //CIRCULACIÓN CULTURA
        //'culturalSeedbeds' => CulturalSeedbedController::class, //SEMILLERO CULTURAL
        'methodologicalMonitorings' => MethodologicalMonitoringController::class, //SEGUIMIENTO METODOLÓGICO
        // 'strengtheningOfMonitorings' => StrengtheningOfMonitoringController::class, // FORTALECIMINETO AL SEGUIMIENTO
        // 'monitoringReports' => MonitoringReportController::class, //INFORMES DE SEGUIMIENTO
        // 'strengtheningSuperMonIns' => StrengtheningSupervisionMonitorsInstructorsController::class, //FORTALECIMIENTO A LA SUPERVISIÓN MONITORES E INSTRUCTORES
        // 'strengtheningSupervisionMans' => StrengtheningSupervisionManagerController::class,  //FORTALECIMIENTO A LA SUPERVISIÓN GESTORES
        'strengtheningTerritories'=>StrengtheningTerritoryController::class, //FORTALECIMIENTO A TERRITORIOS
        'supervisoryReports'=>SupervisoryReportController::class, //INFORME DE SUPERVISIÓN
        'groups'=>GroupController::class
    ]);

    // INFORMES DE SEGUIMIENTO
    Route::apiResource('monitoringReports', 'App\Http\Controllers\V1\MonitoringReportController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('monitoringReports/{id}', [MonitoringReportController::class, 'update'])->name('monitoringReports.update');

    // FORTALECIMIENTO METODOLÓGICO
    Route::apiResource('methodologicalStrengthenings', 'App\Http\Controllers\V1\MethodologicalStrengtheningController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('methodologicalStrengthenings/{id}', [MethodologicalStrengtheningController::class, 'update'])->name('methodologicalStrengthenings.update');

    // ACOMPAÑAMIENTO METODOLÓGICO
    Route::apiResource('methodologicalAccompaniments', 'App\Http\Controllers\V1\MethodologicalAccompanimentController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('methodologicalAccompaniments/{id}', [MethodologicalAccompanimentController::class, 'update'])->name('methodologicalAccompaniments.update');

    // Bitacora de visita territorio
    Route::apiResource('binnacle_territories', 'App\Http\Controllers\V1\BinnacleTerritorieController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('binnacle_territories/{id}', [BinnacleTerritorieController::class, 'update'])->name('binnacle_territories.update');
    Route::get('getAllByUserLogged', [BinnacleTerritorieController::class, 'getAllByUserLogged'])->name('binnacle_territories.getAllByUserLogged');
    Route::get('getRole/{id}', [BinnacleTerritorieController::class, 'getRoles'])->name('binnacle_territories.getRoles');
    Route::get('getRoleCustom/{id}', [BinnacleTerritorieController::class, 'getRolesCustom'])->name('binnacle_territories.getRolesCustom');
    Route::get('getUser/{id}', [BinnacleTerritorieController::class, 'getUsuarios'])->name('binnacle_territories.getUsuarios');

    // Visita Supervición Gestores
    Route::apiResource('strengtheningSupervisionMans', 'App\Http\Controllers\V1\StrengtheningSupervisionManagerController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('strengtheningSupervisionMans/{id}', [StrengtheningSupervisionManagerController::class, 'update'])->name('strengtheningSupervisionMans.update');
    Route::get('strengtheningSupervisionMans/getUsers/{id}', [StrengtheningSupervisionManagerController::class, 'getUsers'])->name('strengtheningSupervisionMans.getUsers');
    Route::get('strengtheningSupervisionMans/methodologicalInstructionGestor/{id}', [StrengtheningSupervisionManagerController::class, 'methodologicalInstructionGestor'])->name('strengtheningSupervisionMans.methodologicalInstructionGestor');


    Route::get('getUsersByNac/{nac}', [GeneralController::class, 'getUsersByNac']);
    Route::get('getRolesByNac/{nac}', [GeneralController::class, 'getRolesByNac']);
    Route::get('getRole', [GeneralController::class, 'getRoles'])->name('general.getRole');
    Route::post('getRolesUsers', [GeneralController::class, 'getRolesUsers']);

    Route::get('basicUsers', [GeneralController::class, 'usersInUserEdit']);


    Route::get('alertsPaginate', [AlertController::class, 'getPaginate'])->name('alert.getPaginate');
    Route::post('alerts', [AlertController::class, 'create'])->name('alert.create');
    Route::delete('alerts/{id}', [AlertController::class, 'destroy'])->name('alert.destroy');

    Route::get('notifications', [NotificationController::class, 'get'])->name('notification.get');
    Route::get('notifications/authenticated', [NotificationController::class, 'getByAuthenticated'])->name('notification.authenticated');
    Route::put('notifications/markAsRead/{id}', [NotificationController::class, 'markAsRead'])->name('notification.markAsRead');
    Route::put('notifications/trash/{id}', [NotificationController::class, 'trash'])->name('notification.trash');

    // Actividad de la plataforma
    Route::apiResource('activityLogs', 'App\Http\Controllers\V1\ActivityLogController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('activityLogs/{id}', [ActivityLogController::class, 'update'])->name('activityLogs.update');

    //Bitacora monitor
    Route::apiResource('binnacles', 'App\Http\Controllers\V1\BinnacleController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('binnacles/{id}', [BinnacleController::class, 'update'])->name('binnacles.update');

    //Bitacora gestor
    Route::apiResource('binnacleManagers', 'App\Http\Controllers\V1\BinnacleManagerController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('binnacleManagers/{id}', [BinnacleManagerController::class, 'update'])->name('binnacleManagers.update');

    //Cultural Circulation
    Route::apiResource('culturalCirculations', 'App\Http\Controllers\V1\CulturalCirculationController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('culturalCirculations/{id}', [CulturalCirculationController::class, 'update'])->name('culturalCirculations.update');

    //Cultural Ensembles
    Route::apiResource('culturalEnsembles', 'App\Http\Controllers\V1\CulturalEmsembleController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('culturalEnsembles/{id}', [CulturalEmsembleController::class, 'update'])->name('culturalEnsembles.update');

    //Cultural Seedbeds
    Route::apiResource('culturalSeedbeds', 'App\Http\Controllers\V1\CulturalSeedbedController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('culturalSeedbeds/{id}', [CulturalSeedbedController::class, 'update'])->name('culturalSeedbeds.update');

    // FORTALECIMINETO AL SEGUIMIENTO
    Route::apiResource('strengtheningOfMonitorings', 'App\Http\Controllers\V1\StrengtheningOfMonitoringController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('strengtheningOfMonitorings/{id}', [StrengtheningOfMonitoringController::class, 'update'])->name('strengtheningOfMonitorings.update');

    // FORTALECIMIENTO A LA SUPERVISIÓN MONITORES E INSTRUCTORES
    Route::apiResource('strengtheningSuperMonIns', 'App\Http\Controllers\V1\StrengtheningSupervisionMonitorsInstructorsController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('strengtheningSuperMonIns/{id}', [StrengtheningSupervisionMonitorsInstructorsController::class, 'update'])->name('strengtheningSuperMonIns.update');

    //Informe mensual
    Route::apiResource('monthly_monitoring_reports', 'App\Http\Controllers\V1\MonthlyMonitoringReportsController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('monthly_monitoring_reports/{id}', [MonthlyMonitoringReportsController::class, 'update'])->name('monthly_monitoring_reports.update');

    Route::put('parentschools/{parentschool}/restore', [ParentSchoolController::class, 'restore'])->name('restoreParentSchool');
    // referencia Bitácora Psicopedagógica
    Route::apiResource('psychopedagogicallogs', 'App\Http\Controllers\V1\PsychopedagogicalLogBookController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('psychopedagogicallogs/{psychopedagogicallog}', [PsychopedagogicalLogBookController::class, 'update'])->name('psychopedagogicallogs.update');
    Route::put('psychopedagogicallogs/{psychopedagogicalog}/restore', [PsychopedagogicalLogBookController::class, 'restore'])->name('restorePsychopedagogicalLogBook');
    //references/monitors retorna lo monitores para parentschool
    Route::get('references/monitors', [ParentSchoolController::class, 'getMonitor'])->name('getMonitorParentSchool');
    //PEC
    Route::apiResource('pecs', 'App\Http\Controllers\V1\Monitors\PecController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('pecs/{pec}', [PecController::class, 'update'])->name('pecs.update');
    Route::get('getPecsCreatedBy/{created_by}', [PecController::class, 'getPecsCreatedBy'])->name('pecs.getPecsCreatedBy');
    //Inscriptions
    Route::apiResource('inscriptions', 'App\Http\Controllers\V1\Monitors\InscriptionController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('inscriptions/{inscription}', [InscriptionController::class, 'update'])->name('inscriptions.update');
    //Inscriptions
    Route::apiResource('methodologicalsheetstwo', 'App\Http\Controllers\V1\MethodologicalSheetsTwoController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('methodologicalsheetstwo/{methodologicalsheetstwo}', [MethodologicalSheetsTwoController::class, 'update'])->name('methodologicalsheetstwo.update');

    Route::get('pecs/consecutive/generate', [PecController::class, 'getConsecutive']);
    Route::post('pecs/query/byRangeActvityDate', [PecController::class, 'getByRangeActivityDate']);

    // METHODOLOGICALINSTRUCTION
    Route::apiResource('methodologicalInstructions', 'App\Http\Controllers\V1\MethodologicalInstructionController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('methodologicalInstructions/{methodologicalInstruction}', [MethodologicalInstructionController::class, 'update'])->name('methodologicalInstructions.update');

    // DIALOGUETABLES
    Route::apiResource('dialoguetables', 'App\Http\Controllers\V1\DialogueTableController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('dialoguetables/{dialoguetable}', [DialogueTableController::class, 'update'])->name('dialoguetables.update');

    // SHOW CULTURAL
    Route::apiResource('binnacleculturalshow', 'App\Http\Controllers\V1\CulturalShowController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('binnacleculturalshow/{binnacleculturalshow}', [CulturalShowController::class, 'update'])->name('binnacleculturalshow.update');


    // USER
    Route::get('usersNoPaginate', [UserController::class, 'noPaginate']);
    Route::post('getUserForRole/{slug}', [UserController::class, 'getUserForRole'])->name('getUserForRole');

    // PROFILES
    Route::get('findByGestorId/{id}', [ProfileController::class, 'findByGestorId']);

    Route::get('config-clear', function () {
        Artisan::call('config:clear');
        echo '<a href=' . url('dashboard') . '>Se ha limpiado la configuración, volver al sistema.</a>';
    });
    Route::get('rollback', function () {
        Artisan::call('c:a');
        echo '<a href=' . url('dashboard') . '>Se ha limpiado la configuración, volver al sistema.</a>';
    });

    Route::get('get-data-selects', [GeneralController::class, 'getDataSelects']);
    Route::put('parentschools/{parentschool}/restore', [ParentSchoolController::class, 'restore'])->name('restoreParentSchool');
    //references/monitors retorna lo monitores para parentschool
    Route::get('references/monitors', [ParentSchoolController::class, 'getMonitor'])->name('getMonitorParentSchool');

    Route::get('psychosocialinstructions/consecutive/generate', [PsychosocialInstructionController::class, 'getConsecutive'])->name('getConsecutive');
    Route::apiResource('psychosocialinstructions', 'App\Http\Controllers\V1\psychosocial\PsychosocialInstructionController')->only(['index', 'store', 'show', 'destroy']);
    Route::post('psychosocialinstructions/{psychosocialinstruction}', [PsychosocialInstructionController::class, 'update'])->name('psychosocialinstructions.update');
    Route::post('pedagogicals/byRangeActvityDate', [PedagogicalController::class, 'getByRangeActivityDate']);
    Route::get('consecutive/generate/{table}/{abreviature}', [GeneralController::class, 'getConsecutive'])->name('getConsecutiveGenerate');
    Route::prefix('exports')->group(function () {
        Route::post('excel/{type_excel?}', [ReportController::class, 'exportExcel'])->name('exportExcel');
        /* Route::prefix('pdf')->group(function () {
            Route::post('parentschools/{type_pdf?}', [PDFController_V1::class, 'formateParentSchools']);
            Route::post('psychopedagogicallogs/{type_pdf?}', [PDFController_V1::class, 'formatePsychoPedagogicallogs']);
            Route::post('beneficiariesMonitor/{type_pdf?}', [PDFController_V1::class, 'formateInscriptionBeneficiaries']);
            Route::post('dialogueTables/{type_pdf?}', [PDFController_V1::class, 'formatDialogueTables']);
            Route::post('psychosocialInstructions/{type_pdf?}', [PDFController_V1::class, 'formatePsychosocialInstructions']);
            Route::post('pedagogicals/{type_pdf?}', [PDFController_V1::class, 'formatePedagogicals']);
            Route::post('methodologicalInstructionModels/{type_pdf?}', [PDFController_V1::class, 'formateMethodologicalInstructions']);
            Route::post('managerMonitorings/{type_pdf?}', [PDFController_V1::class, 'formateManagerMonitorings']);
        }); */
        Route::prefix('download')->group(function () {
            Route::get('/{type_pdf?}', [PDFController_V1::class, 'Download']);
        });
    });

    Route::prefix('generateCut')->group(function () {
        Route::post('excel/{type_excel?}', [GenerateCutJobController::class, 'general'])->name('generateCutExcel');
    });

    /* GENERAR PDF POR PRODUCTO */
    Route::get('generate/pdf/{id}', [GeneratorPDFController::class, 'generate']);

    Route::post('management', [ManagementController::class, 'send_management'])->name('send_management');
    Route::post('users/changePassword', [UserController::class, 'changePassword'])->name('users.changePassword');
    Route::post('users/changeStatus', [UserController::class, 'changeStatus'])->name('users.changeStatus');
    Route::get('getCountDataForm', [GeneralController::class, 'getDataForm'])->name('getDataForm');

    Route::get('getCountLimit/{table}', [MethodologicalSheetsOneController::class, 'getCountLimit'])->name('methodologicalsheetsone.getCountLimit');

    Route::get('getPollOnly', [GeneralController::class, 'getPollOnly'])->name('getPollOnly');
    Route::get('getGroups', [GroupController::class, 'getGroups'])->name('getGroups');
    Route::get('getGroupsCreatedBy/{createdBy}', [GroupController::class, 'getGroupsCreatedBy'])->name('getGroupsCreatedBy');
    Route::get('getAllDataCreatedBy/{table_name}', [GeneralController::class, 'getAllDataCreatedBy'])->name('getAllDataCreatedBy');
    Route::get('getDataSheet/{table}/{date}/{column?}', [GeneralController::class, 'getDataSheet'])->name('getDataSheet');

    /* SELECTS VIEJOS */
    Route::get('get-data-selects', [GeneralController::class, 'getDataSelects']);
    Route::get('selectFromTable', [GeneralController::class, 'selectFromTable']);
    Route::get('selectFromDefaults', [GeneralController::class, 'selectFromDefaults']);
    Route::get('getChangeDataModels', [GeneralController::class, 'getChangeDataModels'])->name('changeDataModels.index');
    Route::get('getChangeDataModels/{id}', [GeneralController::class, 'getChangeDataModels'])->name('changeDataModels.show');
    Route::delete('destroyChangeDataModel/{id}', [GeneralController::class, 'destroyChangeDataModel'])->name('changeDataModels.destroy');
    Route::get('profileRoleUserNac/{id}', [GeneralController::class, 'profileRoleUserNac']);

    /* SELECTS  INDEPENDIENTES */
    Route::get('getGroupBeneficiaries/{id?}/{user_id}', [InscriptionController::class, 'getGroupBeneficiaries'])->name('getGroupBeneficiaries')->where(['id' => '[0-9]+']);
    Route::get('get_neighborhoods/{nac_id}', [NeighborhoodController::class, 'getSelectNeighborhood'])->name('getSelectNeighborhood');
    Route::get('get_nacs', [NacController::class, 'getSelectNacs'])->name('getSelectNacs');
    Route::get('get_place_types', [GeneralController::class, 'getSelectPlaceTypes'])->name('getSelectPlaceTypes');
    Route::get('get_status', [GeneralController::class, 'getSelectStatus'])->name('getSelectStatus');
    Route::get('get_group_beneficiaries', [GeneralController::class, 'getSelectGroupBeneficiaries'])->name('getSelectGroupBeneficiaries');
    Route::get('get_beneficiaries_table', [GeneralController::class, 'getSelectBeneficiariesTable'])->name('getSelectBeneficiariesTable');
    Route::get('get_expertise', [ExpertiseController::class, 'getSelectExpertise'])->name('getSelectExpertise');
    Route::get('get_orientation', [OrientationController::class, 'getSelectOrientation'])->name('getSelectOrientation');
    Route::get('get_cultural_rigtht', [CulturalRightController::class, 'getSelectCulturalRigtht'])->name('getSelectCulturalRigtht');
    Route::get('get_binnacles', [GeneralController::class, 'getSelectBinnacles'])->name('getSelectBinnacles');
    Route::get('get_filter_level', [GeneralController::class, 'getSelectFilterLevel'])->name('getSelectFilterLevel');
    Route::get('get_values', [GeneralController::class, 'getSelectValues'])->name('getSelectValues');

    Route::get('get_lineaments', [GeneralController::class, 'getSelectLineaments'])->name('getSelectLineaments');

    Route::get('get_linkage_projects', [GeneralController::class, 'getSelectLinkageProjects'])->name('getSelectLinkageProjects');
    Route::get('get_disability_types', [GeneralController::class, 'getSelectDisabilityTypes'])->name('getSelectDisabilityTypes');
    Route::get('get_educational_levels', [GeneralController::class, 'getSelecteducationallevels'])->name('getSelecteducationallevels');
    Route::get('get_ethnicities', [GeneralController::class, 'getSelectethnicities'])->name('getSelectethnicities');
    Route::get('get_health_conditions', [GeneralController::class, 'getSelectHealthConditions'])->name('getSelectHealthConditions');
    Route::get('get_conditions', [GeneralController::class, 'getSelectConditions'])->name('getSelectConditions');
    Route::get('get_medical_services', [GeneralController::class, 'getSelectMedicalServices'])->name('getSelectMedicalServices');
    Route::get('get_participant_types', [GeneralController::class, 'getSelectParticipantTypes'])->name('getSelectParticipantTypes');
    Route::get('get_decisions', [GeneralController::class, 'getSelectDecisions'])->name('getSelectDecisions');
    Route::get('get_stratums', [GeneralController::class, 'getSelectStratums'])->name('getSelectStratums');
    Route::get('get_type_documents', [GeneralController::class, 'getSelectTypeDocuments'])->name('getSelectTypeDocuments');
    Route::get('get_zones', [GeneralController::class, 'getSelectZones'])->name('getSelectZones');
    Route::get('get_activation_mode', [GeneralController::class, 'getSelectActivationMode'])->name('getSelectActivationMode');
    Route::get('get_disease_types', [GeneralController::class, 'getSelectDiseaseTypes'])->name('getSelectDiseaseTypes');
    Route::get('get_marital_status', [GeneralController::class, 'getSelectMaritalStatus'])->name('getSelectMaritalStatus');
    Route::get('get_relationship_households', [GeneralController::class, 'getSelectRelationshipHouseholds'])->name('getSelectRelationshipHouseholds');
    Route::get('get_single_registry_victims', [GeneralController::class, 'getSelectSingleRegistryVictims'])->name('getSelectSingleRegistryVictims');
    Route::get('get_type_diseases', [GeneralController::class, 'getSelectTypeDiseases'])->name('getSelectTypeDiseases');
    Route::get('get_beneficiary_attrition_factors', [GeneralController::class, 'getSelectBeneficiaryAttritionFactors'])->name('getSelectBeneficiaryAttritionFactors');
    Route::get('get_relationships', [GeneralController::class, 'getSelectRelationships'])->name('getSelectRelationships');
    Route::get('get_strengthening_types', [GeneralController::class, 'getSelectStrengthening_types'])->name('getSelectStrengthening_types');
    Route::get('get_aspects', [GeneralController::class, 'getSelectAspects'])->name('getSelectAspects');
    Route::get('get_evaluate_aspects', [GeneralController::class, 'getSelectEvaluate_aspects'])->name('getSelectEvaluate_aspects');
});

// Rutas sin auth
Route::prefix('v1')->group(function () {
    Route::get('alerts', [AlertController::class, 'get'])->name('alert.get');
});
//Ruta de prueba
// Route::get('get-data', [GeneralController::class, 'getData']);
// Route::get('prueba', [BinnacleTerritorieController::class, 'index']);
// Route::get('activityLogs', [ActivityLogController::class, 'index'])->name('activityLogs');
