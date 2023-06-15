<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $database_path =[
            database_path('/seeders/sql/users.sql'),
            database_path('/seeders/sql/profiles.sql'),
            database_path('/seeders/sql/assistants.sql'),
            database_path('/seeders/sql/attendants.sql'),
            database_path('/seeders/sql/beneficiaries.sql'),
            database_path('/seeders/sql/binnacles.sql'),
            database_path('/seeders/sql/groups.sql'),
            database_path('/seeders/sql/binnacle_territories.sql'),
            database_path('/seeders/sql/health_datas.sql'),
            database_path('/seeders/sql/socio_demos.sql'),
            database_path('/seeders/sql/inscriptions.sql'),
            database_path('/seeders/sql/manager_monitorings.sql'),
            database_path('/seeders/sql/methodological_instructions.sql'),
            database_path('/seeders/sql/methodological_sheets_one.sql'),
            database_path('/seeders/sql/methodological_sheets_two.sql'),
            database_path('/seeders/sql/monitor_psicosocial_instructions.sql'),
            database_path('/seeders/sql/parent_schools.sql'),
            database_path('/seeders/sql/pecs.sql'),
            database_path('/seeders/sql/polls_desertion.sql'),
            database_path('/seeders/sql/polls.sql'),
            database_path('/seeders/sql/psychopedagogical_logbook_assistance_monitors.sql'),
            database_path('/seeders/sql/psychopedagogical_logbooks.sql'),
            database_path('/seeders/sql/psychosocial_instructions.sql'),
            database_path('/seeders/sql/assistant_psicosocial_instructions.sql'),
            database_path('/seeders/sql/beneficiary_binnacle.sql'),
            database_path('/seeders/sql/beneficiary_pec.sql'),
            database_path('/seeders/sql/pedagogicals.sql'),
            database_path('/seeders/sql/dialogue_tables.sql'),
            database_path('/seeders/sql/binnacle_cultural_show.sql'),
            database_path('/seeders/sql/monthly_monitoring_reports.sql'),
            database_path('/seeders/sql/monitoring_reports.sql'),
            database_path('/seeders/sql/methodological_monitorings.sql'),
            database_path('/seeders/sql/methodological_monitorings_aggregates.sql'),
            database_path('/seeders/sql/strengthening_of_monitorings.sql'),
            database_path('/seeders/sql/parent_school_assistance_monitors.sql'),
            database_path('/seeders/sql/assistant_methodological_instruction.sql'),
            database_path('/seeders/sql/assistant_dialogue_table.sql'),
            database_path('/seeders/sql/cultural_circulations.sql'),
            database_path('/seeders/sql/cultural_ensembles.sql'),
            database_path('/seeders/sql/cultural_seedbeds.sql'),
            database_path('/seeders/sql/methodological_accompaniments.sql'),
            database_path('/seeders/sql/methodological_accompaniment_user.sql'),
            database_path('/seeders/sql/methodological_strengthenings.sql'),
            database_path('/seeders/sql/methodological_strengthening_user.sql'),
            database_path('/seeders/sql/beneficiary_cultural_seedbed.sql'),
            database_path('/seeders/sql/strengthening_super_mons_insts.sql'),
            database_path('/seeders/sql/strengthening_super_mangs.sql'),

        ];

        foreach ($database_path as $sql) {
            DB::unprepared(file_get_contents($sql));
        }
    }
}
