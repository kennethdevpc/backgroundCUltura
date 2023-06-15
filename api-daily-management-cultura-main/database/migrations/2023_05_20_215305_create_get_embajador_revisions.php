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
            CREATE OR REPLACE VIEW get_embajador_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM binnacle_cultural_show b WHERE b.created_by = u.id AND b.status = "ENREV")
                            AS en_revision,
                        (SELECT COUNT(*) FROM binnacle_cultural_show b WHERE b.created_by = u.id AND b.status = "REC")
                            AS rechazadas,
                    (SELECT COUNT(*) FROM binnacle_cultural_show b WHERE b.created_by = u.id AND b.status = "REV")
                            AS revisadas,
                    (SELECT COUNT(*) FROM binnacle_cultural_show b WHERE b.created_by = u.id AND b.status = "APRO")
                            AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 15
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
        $views= "DROP VIEW get_embajador_revisions;";
        DB::unprepared($views);
    }
};
