<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $views = '
            CREATE OR REPLACE VIEW get_binnacleCulturalSeedbedBeneficiary AS
                SELECT
                    u.name AS name_user, p.document_number AS user_doc_number, r.name AS role,
                    n.name AS nac_nac, b.full_name, b.document_number AS bene_doc_number, cs.consecutive, cs.date
                FROM beneficiary_cultural_seedbed bcs
                    INNER JOIN cultural_seedbeds cs ON cs.id = bcs.cultural_seedbed_id
                    INNER JOIN users u ON u.id = cs.created_by
                    INNER JOIN profiles p ON p.user_id = u.id
                    INNER JOIN roles r ON r.id = p.role_id
                    INNER JOIN nacs n ON n.id = p.nac_id
                    INNER JOIN beneficiaries b ON b.id = bcs.beneficiary_id
                WHERE exists (select * FROM `cultural_seedbeds`
                WHERE bcs.`cultural_seedbed_id` = `cultural_seedbeds`.`id`
                AND `cultural_seedbeds`.`created_by`
                NOT IN (1, 2) and `cultural_seedbeds`.`deleted_at` is NULL)
                AND exists (select * from `cultural_seedbeds` where bcs.`cultural_seedbed_id` = `cultural_seedbeds`.`id` and exists (select * from `users` where `cultural_seedbeds`.`created_by` = `users`.`id` and `deleted_at` is null and `users`.`deleted_at` is NULL)
                and `cultural_seedbeds`.`deleted_at` is null)
        ';
        DB::unprepared($views);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $views= "DROP VIEW get_binnacleCulturalSeedbedBeneficiary;";
        DB::unprepared($views);
    }
};
