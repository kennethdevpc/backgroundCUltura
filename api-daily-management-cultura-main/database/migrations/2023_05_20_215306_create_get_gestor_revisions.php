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
            CREATE OR REPLACE VIEW get_gestor_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM methodological_instructions mi WHERE mi.created_by = u.id AND mi.status = "ENREV") +
                    (SELECT COUNT(*) FROM dialogue_tables dt WHERE dt.user_id = u.id AND dt.status = "ENREV") +
                    (SELECT COUNT(*) FROM binnacles b WHERE b.created_by = u.id AND b.binnacle_id = "AC" AND b.status = "ENREV") +
                    (SELECT COUNT(*) FROM manager_monitorings ma WHERE ma.user_id = u.id AND ma.status = "ENREV")
                            AS en_revision,
                    (SELECT COUNT(*) FROM methodological_instructions mi WHERE mi.created_by = u.id AND mi.status = "REC") +
                    (SELECT COUNT(*) FROM dialogue_tables dt WHERE dt.user_id = u.id AND dt.status = "REC") +
                    (SELECT COUNT(*) FROM binnacles b WHERE b.created_by = u.id AND b.binnacle_id = "AC" AND b.status = "REC") +
                    (SELECT COUNT(*) FROM manager_monitorings ma WHERE ma.user_id = u.id AND ma.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM methodological_instructions mi WHERE mi.created_by = u.id AND mi.status = "REV") +
                    (SELECT COUNT(*) FROM dialogue_tables dt WHERE dt.user_id = u.id AND dt.status = "REV") +
                    (SELECT COUNT(*) FROM binnacles b WHERE b.created_by = u.id AND b.binnacle_id = "AC" AND b.status = "REV") +
                    (SELECT COUNT(*) FROM manager_monitorings ma WHERE ma.user_id = u.id AND ma.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM methodological_instructions mi WHERE mi.created_by = u.id AND mi.status = "APRO") +
                    (SELECT COUNT(*) FROM dialogue_tables dt WHERE dt.user_id = u.id AND dt.status = "APRO") +
                    (SELECT COUNT(*) FROM binnacles b WHERE b.created_by = u.id AND b.binnacle_id = "AC" AND b.status = "APRO") +
                    (SELECT COUNT(*) FROM manager_monitorings ma WHERE ma.user_id = u.id AND ma.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 13
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
        $views= "DROP VIEW get_gestor_revisions;";
        DB::unprepared($views);
    }
};
