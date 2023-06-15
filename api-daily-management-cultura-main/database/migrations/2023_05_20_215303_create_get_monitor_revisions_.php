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
            CREATE OR REPLACE VIEW get_monitor_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM inscriptions i WHERE i.created_by = u.id AND i.status = "ENREV") +
                    (SELECT COUNT(*) FROM pecs p WHERE p.created_by = u.id AND p.status = "ENREV") +
                    (SELECT COUNT(*) FROM pedagogicals pe WHERE pe.created_by = u.id AND pe.status = "ENREV") +
                    (SELECT COUNT(*) FROM binnacles b WHERE b.created_by = u.id AND b.binnacle_id = "JP" AND b.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*) FROM inscriptions i WHERE i.created_by = u.id AND i.status = "REC") +
                    (SELECT COUNT(*) FROM pecs p WHERE p.created_by = u.id AND p.status = "REC") +
                    (SELECT COUNT(*) FROM pedagogicals pe WHERE pe.created_by = u.id AND pe.status = "REC") +
                    (SELECT COUNT(*) FROM binnacles b WHERE b.created_by = u.id AND b.binnacle_id = "JP" AND b.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM inscriptions i WHERE i.created_by = u.id AND i.status = "REV") +
                    (SELECT COUNT(*) FROM pecs p WHERE p.created_by = u.id AND p.status = "REV") +
                    (SELECT COUNT(*) FROM pedagogicals pe WHERE pe.created_by = u.id AND pe.status = "REV") +
                    (SELECT COUNT(*) FROM binnacles b WHERE b.created_by = u.id AND b.binnacle_id = "JP" AND b.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM inscriptions i WHERE i.created_by = u.id AND i.status = "APRO") +
                    (SELECT COUNT(*) FROM pecs p WHERE p.created_by = u.id AND p.status = "APRO") +
                    (SELECT COUNT(*) FROM pedagogicals pe WHERE pe.created_by = u.id AND pe.status = "APRO") +
                    (SELECT COUNT(*) FROM binnacles b WHERE b.created_by = u.id AND b.binnacle_id = "JP" AND b.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 14
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
        $views= "DROP VIEW get_monitor_revisions;";
        DB::unprepared($views);
    }
};
