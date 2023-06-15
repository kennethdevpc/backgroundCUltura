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
            CREATE OR REPLACE VIEW get_binnacleImpact AS
                SELECT
                    u.name AS name_user, p.document_number AS doc_number_user, r.name AS role, n.name AS nac,
                    bene.full_name, bene.document_number AS doc_number_bene,
                    b.consecutive, e.name AS expertise_name, b.activity_date
                FROM beneficiary_binnacle bb
                    INNER JOIN binnacles b ON b.id = bb.binnacle_id
                    INNER JOIN beneficiaries be ON be.id = bb.beneficiary_id
                    INNER JOIN users u ON u.id = b.created_by
                    INNER JOIN profiles p ON p.user_id = u.id
                    INNER JOIN roles r ON r.id = p.role_id
                    INNER JOIN nacs n ON n.id = p.nac_id
                    INNER JOIN beneficiaries bene ON bene.id = bb.beneficiary_id
                    INNER JOIN expertises e ON e.id = b.expertise_id
                WHERE EXISTS
                    (SELECT * FROM `binnacles` WHERE bb.binnacle_id = bb.id and exists (select * from `users` where `binnacles`.`created_by` = `users`.`id` and `deleted_at` is null and `users`.`deleted_at` is NULL)
                AND `binnacles`.`deleted_at` is null)
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
        $views= "DROP VIEW get_binnacleImpact;";
        DB::unprepared($views);
    }
};
