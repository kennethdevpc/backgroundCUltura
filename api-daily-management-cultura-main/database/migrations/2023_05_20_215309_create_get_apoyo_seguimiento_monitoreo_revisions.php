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
            CREATE OR REPLACE VIEW get_apoyo_seguimiento_monitoreo_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM strengthening_of_monitorings sm WHERE sm.created_by = u.id AND sm.status = "ENREV") +
                    (SELECT COUNT(*) FROM monitoring_reports mr WHERE mr.created_by = u.id AND mr.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*) FROM strengthening_of_monitorings sm WHERE sm.created_by = u.id AND sm.status = "REC") +
                    (SELECT COUNT(*) FROM monitoring_reports mr WHERE mr.created_by = u.id AND mr.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM strengthening_of_monitorings sm WHERE sm.created_by = u.id AND sm.status = "REV") +
                    (SELECT COUNT(*) FROM monitoring_reports mr WHERE mr.created_by = u.id AND mr.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM strengthening_of_monitorings sm WHERE sm.created_by = u.id AND sm.status = "APRO") +
                    (SELECT COUNT(*) FROM monitoring_reports mr WHERE mr.created_by = u.id AND mr.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 4
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
        $views= "DROP VIEW get_apoyo_seguimiento_monitoreo_revisions;";
        DB::unprepared($views);
    }
};
