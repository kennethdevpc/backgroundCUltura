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
            CREATE OR REPLACE VIEW get_apoyo_psicosocial_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM psychopedagogical_logbooks pl WHERE pl.user_id = u.id AND pl.status = "ENREV") +
                    (SELECT COUNT(*) FROM psychosocial_instructions ps WHERE ps.user_id = u.id AND ps.status = "ENREV") +
                    (SELECT COUNT(*) FROM parent_schools pch WHERE pch.created_by = u.id AND pch.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*) FROM psychopedagogical_logbooks pl WHERE pl.user_id = u.id AND pl.status = "REC") +
                    (SELECT COUNT(*) FROM psychosocial_instructions ps WHERE ps.user_id = u.id AND ps.status = "REC") +
                    (SELECT COUNT(*) FROM parent_schools pch WHERE pch.created_by = u.id AND pch.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM psychopedagogical_logbooks pl WHERE pl.user_id = u.id AND pl.status = "REV") +
                    (SELECT COUNT(*) FROM psychosocial_instructions ps WHERE ps.user_id = u.id AND ps.status = "REV") +
                    (SELECT COUNT(*) FROM parent_schools pch WHERE pch.created_by = u.id AND pch.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM psychopedagogical_logbooks pl WHERE pl.user_id = u.id AND pl.status = "APRO") +
                    (SELECT COUNT(*) FROM psychosocial_instructions ps WHERE ps.user_id = u.id AND ps.status = "APRO") +
                    (SELECT COUNT(*) FROM parent_schools pch WHERE pch.created_by = u.id AND pch.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 8
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
        $views= "DROP VIEW get_apoyo_psicosocial_revisions;";
        DB::unprepared($views);
    }
};
