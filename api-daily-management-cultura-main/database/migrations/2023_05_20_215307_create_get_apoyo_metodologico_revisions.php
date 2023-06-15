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
            CREATE OR REPLACE VIEW get_apoyo_metodologico_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM methodological_accompaniments ma WHERE ma.created_by = u.id AND ma.status = "ENREV") +
                    (SELECT COUNT(*) FROM methodological_strengthenings ms WHERE ms.created_by = u.id AND ms.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*) FROM methodological_accompaniments ma WHERE ma.created_by = u.id AND ma.status = "REC") +
                    (SELECT COUNT(*) FROM methodological_strengthenings ms WHERE ms.created_by = u.id AND ms.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM methodological_accompaniments ma WHERE ma.created_by = u.id AND ma.status = "REV") +
                    (SELECT COUNT(*) FROM methodological_strengthenings ms WHERE ms.created_by = u.id AND ms.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM methodological_accompaniments ma WHERE ma.created_by = u.id AND ma.status = "APRO") +
                    (SELECT COUNT(*) FROM methodological_strengthenings ms WHERE ms.created_by = u.id AND ms.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 3
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
        $views= "DROP VIEW get_apoyo_metodologico_revisions;";
        DB::unprepared($views);
    }
};
