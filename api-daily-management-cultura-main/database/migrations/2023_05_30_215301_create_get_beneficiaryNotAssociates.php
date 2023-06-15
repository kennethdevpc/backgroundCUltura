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
            CREATE OR REPLACE VIEW get_beneficiaryNotAssociates AS
                SELECT u.name AS user, p.document_number AS number_doc_user, r.name AS role, n.name AS nac, b.full_name, b.document_number AS number_doc_bene
                FROM beneficiaries b
                    INNER JOIN users u ON u.id = b.created_by
                    INNER JOIN profiles p ON p.user_id = u.id
                    INNER JOIN roles r ON r.id = p.role_id
                    INNER JOIN nacs n ON n.id = p.nac_id
                WHERE NOT EXISTS
                    (SELECT * FROM `binnacles` inner join `beneficiary_binnacle` on `binnacles`.`id` = `beneficiary_binnacle`.`binnacle_id` where b.id = `beneficiary_binnacle`.`beneficiary_id` and `binnacles`.`deleted_at` is NULL)
                AND NOT EXISTS
                    (SELECT * FROM `cultural_seedbeds` inner join `beneficiary_cultural_seedbed` on `cultural_seedbeds`.`id` = `beneficiary_cultural_seedbed`.`cultural_seedbed_id` where b.id = `beneficiary_cultural_seedbed`.`beneficiary_id` and `cultural_seedbeds`.`deleted_at` is NULL)
                AND `created_by` not IN (1, 2)
                AND EXISTS (select * from `users` where b.created_by = `users`.`id` and `deleted_at` is null and `users`.`deleted_at` is null)
                AND b.deleted_at is null
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
        $views= "DROP VIEW get_beneficiaryNotAssociates;";
        DB::unprepared($views);
    }
};
