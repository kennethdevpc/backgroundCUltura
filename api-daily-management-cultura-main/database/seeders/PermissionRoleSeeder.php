<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dashboard = Permission::latest('id')->first()->id;
        //Monitor
        $permissionsAssignMonitor = [
            'inscriptions',
            'pecs',
            'binnacles',
            'pollDesertions',
            'pedagogicals',
            'groups'
        ];

        //Monitor
        foreach ($permissionsAssignMonitor as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.destroy')->get();

            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.monitor'),
                    'permission_id' => $permission->id

                ]);
            }
        }
        //Gestor->monitor

        $permissionsAssignMonitorManager = [
            'pecs',
            'pedagogicals'
        ];
        foreach ($permissionsAssignMonitorManager as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')
                ->get();

            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.gestor'),
                    'permission_id' => $permission->id

                ]);
            }
        }
        $permissionsAssignManager = [

            'dialoguetables',
            'methodologicalInstructions',
            'managermonitorings',
            'binnacleManagers',
            'binnacles',
        ];

        foreach ($permissionsAssignManager as $value) {
            $permissions = Permission::select('id', 'name')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.destroy')->get();

            foreach ($permissions  as  $permission) {
                PermissionRole::create([
                    'role_id' => config('roles.gestor'),
                    'permission_id' => $permission->id
                ]);
            }
        }

        //Instructores

        $rolInstructorPermissionsAssign = [
            'pecs',
            'binnacles',
            'pedagogicals',
            'inscriptions',
            //'polls',
            'methodologicalsheetsone',
            'methodologicalsheetstwo',
            'groups',
        ];


        foreach ($rolInstructorPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.destroy')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.instructor'),
                    'permission_id' => $permission->id

                ]);
            }
        }

        PermissionRole::create([
            'role_id' => config('roles.instructor'),
            'permission_id' => 340 // Menú de instructores
        ]);
        // Lider de instructores
        $rolInstructorLeaderPermissionsAssign = [
            'pecs',
            'methodologicalsheetsone',
            'methodologicalsheetstwo',
            'culturalEnsembles',
            'culturalCirculations', //CIRCULACIÓN CULTURA
            'culturalSeedbeds', //SEMILLERO CULTURAL
        ];
        PermissionRole::create([
            'role_id' => config('roles.lider_instructor'),
            'permission_id' => 340 // Menú de instructores
        ]);

        foreach ($rolInstructorLeaderPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')
                ->where('slug', '!=', $value . '.destroy')
                ->where('slug', '!=', $value . '.create')
                ->where('slug', '!=', $value . '.store')
                ->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.lider_instructor'),
                    'permission_id' => $permission->id

                ]);
            }
        }
        //PsicoSocial

        $rolPsicoPermissionsAssign = [
            'psychosocialinstructions',
            'parentschools',
            'psychopedagogicallogs',


        ];

        foreach ($rolPsicoPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.destroy')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.psicosocial'),
                    'permission_id' => $permission->id

                ]);
            }
        }

        //Apoyo y seguimiento->monitor

        $permissionsAssignMonitorSupport = [
            'inscriptions',
            'binnacles',
            'binnacleManagers',
            'binnacleculturalshow'
        ];

        foreach ($permissionsAssignMonitorSupport as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')
                ->where('slug', '!=', $value . '.create')
                ->where('slug', '!=', $value . '.store')
                ->where('slug', '!=', $value . '.destroy')
                ->get();

            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.apoyo_al_seguimiento_monitoreo'),
                    'permission_id' => $permission->id

                ]);
            }
        }

        //Apoyo metodologico
        $permissionsAssignManager = [
            'dialoguetables',
            'methodologicalInstructions',
            'managermonitorings',
            'binnacleManagers'
        ];

        foreach ($permissionsAssignManager as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.apoyo_metodologico'),
                    'permission_id' => $permission->id

                ]);
            }
        }

        //array_push($rolPsicoPermissionsAssign, 'binnacle_territories');
        //Coordinador psicosocial

        foreach ($rolPsicoPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')->get();
            foreach ($permissions  as  $permission) {
                PermissionRole::create([
                    'role_id' => config('roles.coordinador_psicosocial'),
                    'permission_id' => $permission->id

                ]);
            }
        }
        //Coordinador psicosocial ->opción de menú
        /* PermissionRole::create([
            'role_id' => config('roles.coordinador_psicosocial'),
            'permission_id' => 9

        ]); */

        //Lider instructor
        $rolAmbassadorInstructorPermissionsAssign = [
            'binnacleculturalshow',
        ];

        foreach ($rolAmbassadorInstructorPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.lider_instructor'),
                    'permission_id' => $permission->id

                ]);
            }
        }

        //Coordinador de seguimiento (SEGUIMIENTO, METODOLOGICO, ADMINISTRATIVO, PSICOSOCIAL)
        $rolCoordinatorsPermissionsAssign = [
            'coordinadores',
        ];

        foreach ($rolCoordinatorsPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')->get();
            foreach ($permissions  as  $value) {

                PermissionRole::create([
                    'role_id' => config('roles.coordinador_seguimiento'),
                    'permission_id' => $value->id
                ]);
                PermissionRole::create([
                    'role_id' => config('roles.coordinador_supervision'),
                    'permission_id' => $value->id
                ]);

                PermissionRole::create([
                    'role_id' => config('roles.coordinador_metodologico'),
                    'permission_id' => $permission->id
                ]);
            }
        }
        //Coordinador metodológico

        $rolMethodologicalCoordinatorPermissionsAssign = [
            'binnacles',
            'binnacleManagers',
            'dialoguetables',
            'methodologicalInstructions',
            'managermonitorings',
            //'polls'
        ];

        foreach ($rolMethodologicalCoordinatorPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')->get();
            foreach ($permissions  as  $value) {

                PermissionRole::create([
                    'role_id' => config('roles.coordinador_seguimiento'),
                    'permission_id' => $value->id
                ]);
                PermissionRole::create([
                    'role_id' => config('roles.coordinador_psicosocial'),
                    'permission_id' => $permission->id
                ]);
            }
        }

        //Dasboard
        $roles = Role::select('id')->get();
        foreach ($roles as  $value) {
            PermissionRole::create([
                'role_id' => $value->id,
                'permission_id' => $dashboard
            ]);
        }

        //Asignación de modulos a roles.

        $modules = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; //Permisos

        //Monitor
        PermissionRole::create([
            'role_id' => config('roles.monitor'),
            'permission_id' => $modules[0]
        ]);

        //Gestores
        PermissionRole::create([
            'role_id' => config('roles.gestor'),
            'permission_id' => $modules[1]
        ]);
        //Monitor
        PermissionRole::create([
            'role_id' => config('roles.gestor'),
            'permission_id' => $modules[0]
        ]);

        /*--------------------------------------------------------*/
        //Psicosocial
        PermissionRole::create([
            'role_id' => config('roles.psicosocial'),
            'permission_id' => $modules[2]
        ]);


        /*--------------------------------------------------------*/
        //Coodinador psicosocial
        PermissionRole::create([
            'role_id' => config('roles.coordinador_psicosocial'),
            'permission_id' => $modules[2]
        ]);

        /*--------------------------------------------------------*/
        //Apoyo de seguimiento
        //Monitores
        PermissionRole::create([
            'role_id' => config('roles.apoyo_al_seguimiento_monitoreo'),
            'permission_id' => $modules[0]
        ]);

        /*--------------------------------------------------------*/
        //Instructor
        //Monitores
        PermissionRole::create([
            'role_id' => config('roles.instructor'),
            'permission_id' => $modules[0]
        ]);

        /*--------------------------------------------------------*/
        //Lider de instructores
        //Monitores
        PermissionRole::create([
            'role_id' => config('roles.lider_instructor'),
            'permission_id' => $modules[0]
        ]);

        /*--------------------------------------------------------*/
        //Embajador
        //Monitores
        PermissionRole::create([
            'role_id' => config('roles.embajador'),
            'permission_id' => $modules[0]
        ]);
        //Lider embajador
        PermissionRole::create([
            'role_id' => config('roles.lider_embajador'),
            'permission_id' => $modules[0]
        ]);

        /*--------------------------------------------------------*/
        //Lider de embajador
        //Monitores
        // PermissionRole::create([
        //     'role_id' => config('roles.lider_embajador'),
        //     'permission_id' => $modules[0]
        // ]);

        /*--------------------------------------------------------*/
        //Apoyo metodologico

        PermissionRole::create([
            'role_id' => config('roles.apoyo_metodologico'),
            'permission_id' => $modules[1]
        ]);

        /*--------------------------------------------------------*/
        //Coordinador metodológico
        /* PermissionRole::create([
            'role_id' => config('roles.coordinador_metodologico'),
            'permission_id' => $modules[0]
        ]);

        PermissionRole::create([
            'role_id' => config('roles.coordinador_metodologico'),
            'permission_id' => $modules[1]
        ]); */

        /*--------------------------------------------------------*/
        //Coordinador de supervisión
        // PermissionRole::create([
        //     'role_id' => config('roles.coordinador_supervision'),
        //     'permission_id' => $modules[8]
        // ]);
        $rolCoordinatorSupervisorsPermissionsAssign = [
            'binnacle_territories',
            'strengtheningSuperMonIns',
            'strengtheningSupervisionMans',
            'supervision_reports',
            'phone_reports',
            'supervision_products',
            // Nuevos submenú
            'reportSupervision',
            'coordinator_supervisors',
            'monitors',
            'managers',
            'inscriptions',
            'binnacles',
            'pedagogicals',
            'dialoguetables',
            'pecs',
            'methodologicalInstructions',
            'managermonitorings',
            // 'binnacleManagers',
            'methodologicalsheetsone',
            'methodologicalsheetstwo',
            'culturalEnsembles',
            'culturalSeedbeds', //SEMILLERO CULTURAL
            'culturalCirculations',
            'binnacleculturalshow',
            'pollDesertions',
            'psychosocialinstructions',
            'parentschools',
            'psychopedagogicallogs',
            'reports',
            'strengtheningOfMonitorings',
            // 'monitoringReports',
            'methodologicalAccompaniments',
            'methodologicalStrengthenings',
            'methodologicalMonitorings',
            'reportTerritoryCoordinators'
        ];
        /*--------------------------------------------------------*/
        //Apoyo de supervisión
        // PermissionRole::create([
        //     'role_id' => config('roles.apoyo_supervision'),
        //     'permission_id' => $modules[9]
        // ]);
        $rolSupportSupervisorsPermissionsAssign = [
            'supervision_reports',
            'phone_reports',
            'supervision_products',
            'monthly_monitoring_reports',
            'strengtheningSuperMonIns',
            'strengtheningSupervisionMans'
        ];
        // Coordinador de supervisión
        foreach ($rolCoordinatorSupervisorsPermissionsAssign as $value) {

            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')
                ->where('slug', '!=', $value . '.store')
                ->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')
                ->get();

            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.coordinador_supervision'),
                    'permission_id' => $permission->id

                ]);
            }
        }
        $rolCoordinatorSupervisorsPermission = [
            'coordinadores'
        ];
        foreach ($rolCoordinatorSupervisorsPermission as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')
                ->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.coordinador_supervision'),
                    'permission_id' => $permission->id

                ]);
            }
        }
        $rolSupportSupervisorsViewPermissionsAssign = [
            // Nuevos submenú
            'reportSupervision',
            'coordinator_supervisors',
            'monitors',
            'managers',
            'inscriptions',
            'binnacles',
            'pedagogicals',
            'dialoguetables',
            'pecs',
            'methodologicalInstructions',
            'managermonitorings',
            // 'binnacleManagers',
            'methodologicalsheetsone',
            'methodologicalsheetstwo',
            'culturalEnsembles',
            'culturalSeedbeds', //SEMILLERO CULTURAL
            'culturalCirculations',
            'binnacleculturalshow',
            'pollDesertions',
            'psychosocialinstructions',
            'parentschools',
            'psychopedagogicallogs',
            'reports',
            'strengtheningOfMonitorings',
            // 'monitoringReports',
            'methodologicalAccompaniments',
            'methodologicalStrengthenings',
            'methodologicalMonitorings',
            'reportTerritoryCoordinators'
        ];


        //Apoyo se supervisión
        foreach ($rolSupportSupervisorsPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.apoyo_supervision'),
                    'permission_id' => $permission->id

                ]);
            }
        }
        //Apoyo se supervisión-ver
        foreach ($rolSupportSupervisorsViewPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')
                ->where('slug', '!=', $value . '.store')
                ->where('slug', '!=', $value . '.create')
                ->where('slug', '!=', $value . '.destroy')
                ->where('slug', '!=', $value . '.edit')
                ->where('slug', '!=', $value . '.update')
                ->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.apoyo_supervision'),
                    'permission_id' => $permission->id

                ]);
            }
        }
        // //Admin
        $rolAdminPermissionsAssign = [
            'activityLogs',
            'changeDataModels',
            'reportSupervision',
            'coordinator_supervisors',
            'monitors',
            'managers',
            'inscriptions',
            'binnacles',
            'alerts',
            // 'monthly_monitoring_reports',
            'pedagogicals',
            'dialoguetables',
            'pecs',
            'methodologicalInstructions',
            'managermonitorings',
            // 'binnacleManagers',
            'methodologicalsheetsone',
            'methodologicalsheetstwo',
            'culturalEnsembles',
            'culturalSeedbeds', //SEMILLERO CULTURAL
            'culturalCirculations',
            'binnacleculturalshow',
            'pollDesertions',
            'psychosocialinstructions',
            'parentschools',
            'psychopedagogicallogs',
            'reports',
            'strengtheningSuperMonIns',
            'strengtheningSupervisionMans',
            'strengtheningOfMonitorings',
            // 'monitoringReports',
            'methodologicalAccompaniments',
            'methodologicalStrengthenings',
            'strengtheningTerritories',
            'methodologicalMonitorings',
            'groups',
            'entities',
            'neighborhoods',
            'expertises',
            'orientations',
            'nacs',
            'cultural-rights',
            'users',
            'setting',
            'profiles',
            'polls',
            'reportTerritoryCoordinators'
            // 'binnacle_territories'
        ];

        foreach ($rolAdminPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.root'),
                    'permission_id' => $permission->id

                ]);
            }
        }

        $roles_permission_send = [2, 3, 4, 5, 6, 7, 9, 10, 13, 17, 18,  19]; //id de todos los roles que revisan
        foreach ($roles_permission_send as $value) {
            PermissionRole::create([
                'role_id' =>  $value,
                'permission_id' => $modules[6]
            ]);
        }

        foreach ($modules as $value) {
            if ($value != 4 && $value != 6 && $value != 8) {
                PermissionRole::create([
                    'role_id' => config('roles.root'),
                    'permission_id' => $value

                ]);
            }
        }

        // Permisos de los nuevos formularios
        $permissionNewInstructors = [14, 15, 16];

        foreach ($permissionNewInstructors as $value) {
            PermissionRole::create([
                'role_id' => config('roles.instructor'),
                'permission_id' => $value
            ]);

            PermissionRole::create([
                'role_id' => config('roles.apoyo_al_seguimiento_monitoreo'),
                'permission_id' => $value
            ]);

            PermissionRole::create([
                'role_id' => config('roles.lider_instructor'),
                'permission_id' => $value
            ]);
        }

        //SHOW CULTURAL
        PermissionRole::create([
            'role_id' => 2,
            'permission_id' => 17
        ]);

        PermissionRole::create([
            'role_id' => 3,
            'permission_id' => 17
        ]);

        PermissionRole::create([
            'role_id' => 8,
            'permission_id' => 17
        ]);

        //APOYO METODOLÓGICO
        PermissionRole::create([
            'role_id' => config('roles.apoyo_metodologico'),
            'permission_id' => 17

        ]);
        PermissionRole::create([
            'role_id' => config('roles.apoyo_metodologico'),
            'permission_id' => 19

        ]);

        //LIDER METODOLÓGICO

        // PermissionRole::create([
        //     'role_id' => config('roles.lider_metodologico'),
        //     'permission_id' => 20

        // ]);

        // APOYO AL SEGUIMIENTO Y MONITOREO

        PermissionRole::create([
            'role_id' => config('roles.apoyo_al_seguimiento_monitoreo'),
            'permission_id' => 21

        ]);
        PermissionRole::create([
            'role_id' => config('roles.apoyo_al_seguimiento_monitoreo'),
            'permission_id' => 22

        ]);

        //APOYO A LA SUPERVISIÓN

        // PermissionRole::create([
        //     'role_id' => config('roles.coordinador_supervision'),
        //     'permission_id' => 23

        // ]);
        // PermissionRole::create([
        //     'role_id' => config('roles.apoyo_supervision'),
        //     'permission_id' => 24

        // ]);

        // PermissionRole::create([
        //     'role_id' => config('roles.apoyo_supervision'),
        //     'permission_id' => 24
        // ]);



        $rolFormInstructionsPermissionsAssign = [
            'culturalEnsembles',
            'culturalCirculations', //CIRCULACIÓN CULTURA
            'culturalSeedbeds', //SEMILLERO CULTURAL
        ];

        foreach ($rolFormInstructionsPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')
                ->where('slug', '!=', $value . '.store')
                ->where('slug', '!=', $value . '.create')
                ->where('slug', '!=', $value . '.destroy')
                ->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.instructor'),
                    'permission_id' => $permission->id

                ]);
                PermissionRole::create([
                    'role_id' => config('roles.apoyo_al_seguimiento_monitoreo'),
                    'permission_id' => $permission->id

                ]);
            }
        }


        $rolFormAmbassadorssPermissionsAssign = [
            'binnacleculturalshow',
        ];


        foreach ($rolFormAmbassadorssPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.embajador'),
                    'permission_id' => $permission->id

                ]);
                PermissionRole::create([
                    'role_id' => config('roles.monitor'),
                    'permission_id' => $permission->id

                ]);
                PermissionRole::create([
                    'role_id' => config('roles.lider_embajador'),
                    'permission_id' => $permission->id

                ]);
            }
        }


        $rolFormMethodologicalSupportPermissionsAssign = [
            'methodologicalAccompaniments',
            'methodologicalStrengthenings'
        ];

        foreach ($rolFormMethodologicalSupportPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.apoyo_metodologico'),
                    'permission_id' => $permission->id

                ]);
                PermissionRole::create([
                    'role_id' => config('roles.coordinador_seguimiento'),
                    'permission_id' => $permission->id

                ]);
            }
        }


        $rolFormMethodologicalLeaderPermissionsAssign = [
            'methodologicalMonitorings'
        ];

        foreach ($rolFormMethodologicalLeaderPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->get();
            foreach ($permissions  as  $permission) {
                PermissionRole::create([
                    'role_id' => config('roles.lider_metodologico'),
                    'permission_id' => $permission->id
                ]);
            }
        }


        $rolFormSupportAssuranceMonitoringPermissionsAssign = [
            'strengtheningOfMonitorings',
            'monitoringReports'
        ];

        foreach ($rolFormSupportAssuranceMonitoringPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.apoyo_al_seguimiento_monitoreo'),
                    'permission_id' => $permission->id

                ]);
                PermissionRole::create([
                    'role_id' => config('roles.coordinador_seguimiento'),
                    'permission_id' => $permission->id

                ]);
            }
        }

        // $rolFormSupervisorySupportPermissionsAssign = [
        //     'strengtheningSuperMonIns',
        //     'strengtheningSupervisionMans'
        // ];

        // foreach ($rolFormSupervisorySupportPermissionsAssign as $value) {
        //     $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')
        //     ->where('slug', '!=', $value . '.store')
        //     ->where('slug', '!=', $value . '.create')
        //     ->where('slug', '!=', $value . '.destroy')->get();
        //     foreach ($permissions  as  $permission) {

        //         PermissionRole::create([
        //             'role_id' => config('roles.coordinador_supervision'),
        //             'permission_id' => $permission->id

        //         ]);
        //     }
        // }

        $rolFormNewCoordinatorPermissionsAssign = [
            'strengtheningTerritories'

        ];

        foreach ($rolFormNewCoordinatorPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.coordinador_metodologico'),
                    'permission_id' => $permission->id

                ]);

                PermissionRole::create([
                    'role_id' => config('roles.coordinador_administrativo'),
                    'permission_id' => $permission->id

                ]);
                PermissionRole::create([
                    'role_id' => config('roles.coordinador_psicosocial'),
                    'permission_id' => $permission->id

                ]);
                PermissionRole::create([
                    'role_id' => config('roles.coordinador_supervision'),
                    'permission_id' => $permission->id

                ]);
                PermissionRole::create([
                    'role_id' => config('roles.coordinador_seguimiento'),
                    'permission_id' => $permission->id

                ]);
                PermissionRole::create([
                    'role_id' => config('roles.subdireccion'),
                    'permission_id' => $permission->id

                ]);
            }
        }

        // $rolFormSupervisoryReportsPermissionsAssign = [
        //     'monthly_monitoring_reports'
        // ];

        // foreach ($rolFormSupervisoryReportsPermissionsAssign as $value) {
        //     $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->get();
        //     foreach ($permissions  as  $permission) {

        //         PermissionRole::create([
        //             'role_id' => config('roles.coordinador_supervision'),
        //             'permission_id' => $permission->id

        //         ]);
        //     }
        // }

        //Permisos de encuesta-polls
        $permissions = Permission::whereIn('id', [108, 109])->select('id')->get();
        $rolePolls = Role::all()->except([1, 2, 12]);
        foreach ($permissions as $value) {
            foreach ($rolePolls  as  $rol) {
                PermissionRole::create([
                    'role_id' =>  $rol->id,
                    'permission_id' => $value->id

                ]);
            }
        }


        foreach ($rolePolls  as  $rol) {
            PermissionRole::create([
                'role_id' =>  $rol->id,
                'permission_id' => $modules[3]

            ]);
        }

        $secretaryCulturalPermissions = [
            'reportSupervision',
            'coordinator_supervisors',
            'monitors',
            'managers',
            'inscriptions',
            'binnacles',
            // 'monthly_monitoring_reports',
            'pedagogicals',
            'dialoguetables',
            'pecs',
            'methodologicalInstructions',
            'managermonitorings',
            // 'binnacleManagers',
            'methodologicalsheetsone',
            'methodologicalsheetstwo',
            'culturalEnsembles',
            'culturalSeedbeds', //SEMILLERO CULTURAL
            'culturalCirculations',
            'binnacleculturalshow',
            'pollDesertions',
            'psychosocialinstructions',
            'parentschools',
            'psychopedagogicallogs',
            'reports',
            'strengtheningSuperMonIns',
            'strengtheningSupervisionMans',
            'strengtheningOfMonitorings',
            // 'monitoringReports',
            'methodologicalAccompaniments',
            'methodologicalStrengthenings',
            'methodologicalMonitorings',
            'reportTerritoryCoordinators'
        ];


        foreach ($secretaryCulturalPermissions as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')
                ->where('slug', '!=', $value . '.store')
                ->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')
                ->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.secretaria_cultural'),
                    'permission_id' => $permission->id

                ]);
            }
        }

        $rolSubdirection = [
            'reportsTerritories',
            'subdireccion'
        ];

        foreach ($rolSubdirection as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->get();
            foreach ($permissions  as  $permission) {
                PermissionRole::create([
                    'role_id' => config('roles.subdireccion'),
                    'permission_id' => $permission->id
                ]);
            }
        }

        $rolDirection = [
            'reportSupervision',
            'coordinator_supervisors',
            'monitors',
            'managers',
            'inscriptions',
            'binnacles',
            'monthly_monitoring_reports',
            'pedagogicals',
            'dialoguetables',
            'pecs',
            'methodologicalInstructions',
            'managermonitorings',
            'binnacleManagers',
            'methodologicalsheetsone',
            'methodologicalsheetstwo',
            'culturalEnsembles',
            'culturalSeedbeds', //SEMILLERO CULTURAL
            'culturalCirculations',
            'binnacleculturalshow',
            'pollDesertions',
            'psychosocialinstructions',
            'parentschools',
            'psychopedagogicallogs',
            'reports',
            'strengtheningSuperMonIns',
            'strengtheningSupervisionMans',
            'strengtheningOfMonitorings',
            'monitoringReports',
            'methodologicalAccompaniments',
            'methodologicalStrengthenings',
            'methodologicalMonitorings',
            'reportTerritoryCoordinators'
        ];

        foreach ($rolDirection as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE',  $value . '%')
                ->where('slug', '!=', $value . '.store')
                ->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')
                ->get();
            foreach ($permissions  as  $permission) {
                PermissionRole::create([
                    'role_id' => config('roles.direccion'),
                    'permission_id' => $permission->id
                ]);
            }
        }

        //Coordinador metodologico
        $rolCoordinatorMethodologicalsPermissionsAssign = [
            'methodologicalAccompaniments',
            'methodologicalStrengthenings',
            'methodologicalMonitorings'
        ];

        foreach ($rolCoordinatorMethodologicalsPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.store')->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.coordinador_metodologico'),
                    'permission_id' => $permission->id
                ]);
            }
        }

        //Coordinador de seguimiento
        $rolCoordinatorTrackingsPermissionsAssign = [
            'strengtheningOfMonitorings',
            'monitoringReports',
            // 'culturalSeedbeds'
        ];

        foreach ($rolCoordinatorTrackingsPermissionsAssign as $value) {
            $permissions = Permission::select('id')->where('slug', 'LIKE', '%' . $value . '%')->where('slug', '!=', $value . '.store')->where('slug', '!=', $value . '.create')->where('slug', '!=', $value . '.destroy')->get();
            foreach ($permissions  as  $permission) {

                PermissionRole::create([
                    'role_id' => config('roles.coordinador_seguimiento'),
                    'permission_id' => $permission->id
                ]);
            }
        }
    }
}
