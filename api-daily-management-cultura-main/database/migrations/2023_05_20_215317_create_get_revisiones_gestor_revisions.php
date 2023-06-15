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
            CREATE OR REPLACE VIEW get_revisiones_gestor_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM pecs pe LEFT JOIN profiles p ON p.user_id = pe.created_by WHERE p.gestor_id = u.id AND pe.status = "ENREV") +
                    (SELECT COUNT(*) FROM pedagogicals peda LEFT JOIN profiles p ON p.user_id = peda.created_by WHERE p.gestor_id = u.id AND peda.status = "ENREV") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE p.gestor_id = u.id AND bi.status = "ENREV" AND bi.binnacle_id = "JP")
                        AS en_revision,
                    (SELECT COUNT(*) FROM pecs pe LEFT JOIN profiles p ON p.user_id = pe.created_by WHERE p.gestor_id = u.id AND pe.status = "REC") +
                    (SELECT COUNT(*) FROM pedagogicals peda LEFT JOIN profiles p ON p.user_id = peda.created_by WHERE p.gestor_id = u.id AND peda.status = "REC") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE p.gestor_id = u.id AND bi.status = "REC" AND bi.binnacle_id = "JP")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM pecs pe LEFT JOIN profiles p ON p.user_id = pe.created_by WHERE p.gestor_id = u.id AND pe.status = "REV") +
                    (SELECT COUNT(*) FROM pedagogicals peda LEFT JOIN profiles p ON p.user_id = peda.created_by WHERE p.gestor_id = u.id AND peda.status = "REV") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE p.gestor_id = u.id AND bi.status = "REV" AND bi.binnacle_id = "JP")
                        AS revisadas,
                    (SELECT COUNT(*) FROM pecs pe LEFT JOIN profiles p ON p.user_id = pe.created_by WHERE p.gestor_id = u.id AND pe.status = "APRO") +
                    (SELECT COUNT(*) FROM pedagogicals peda LEFT JOIN profiles p ON p.user_id = peda.created_by WHERE p.gestor_id = u.id AND peda.status = "APRO") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE p.gestor_id = u.id AND bi.status = "APRO" AND bi.binnacle_id = "JP")
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
        $views= "DROP VIEW get_revisiones_gestor_revisions;";
        DB::unprepared($views);
    }
};
