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
            CREATE OR REPLACE VIEW get_lider_metodologico_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM methodological_monitorings mm WHERE mm.created_by = u.id AND mm.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*) FROM methodological_monitorings mm WHERE mm.created_by = u.id AND mm.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM methodological_monitorings mm WHERE mm.created_by = u.id AND mm.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM methodological_monitorings mm WHERE mm.created_by = u.id AND mm.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 20
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
        $views= "DROP VIEW get_lider_metodologico_revisions;";
        DB::unprepared($views);
    }
};
