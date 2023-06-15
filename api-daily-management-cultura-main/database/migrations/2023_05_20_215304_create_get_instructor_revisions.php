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
            CREATE OR REPLACE VIEW get_instructor_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM inscriptions i WHERE i.created_by = u.id AND i.status = "ENREV") +
                    (SELECT COUNT(*) FROM pecs p WHERE p.created_by = u.id AND p.status = "ENREV") +
                    (SELECT COUNT(*) FROM methodological_sheets_one mso WHERE mso.created_by = u.id AND mso.status = "ENREV") +
                    (SELECT COUNT(*) FROM methodological_sheets_two mst WHERE mst.created_by = u.id AND mst.status = "ENREV") +
                    (SELECT COUNT(*) FROM cultural_ensembles ce WHERE ce.created_by = u.id AND ce.status = "ENREV") +
                    (SELECT COUNT(*) FROM cultural_circulations cc WHERE cc.created_by = u.id AND cc.status = "ENREV") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs WHERE cs.created_by = u.id AND cs.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*) FROM inscriptions i WHERE i.created_by = u.id AND i.status = "REC") +
                    (SELECT COUNT(*) FROM pecs p WHERE p.created_by = u.id AND p.status = "REC") +
                    (SELECT COUNT(*) FROM methodological_sheets_one mso WHERE mso.created_by = u.id AND mso.status = "REC") +
                    (SELECT COUNT(*) FROM methodological_sheets_two mst WHERE mst.created_by = u.id AND mst.status = "REC") +
                    (SELECT COUNT(*) FROM cultural_ensembles ce WHERE ce.created_by = u.id AND ce.status = "REC") +
                    (SELECT COUNT(*) FROM cultural_circulations cc WHERE cc.created_by = u.id AND cc.status = "REC") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs WHERE cs.created_by = u.id AND cs.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM inscriptions i WHERE i.created_by = u.id AND i.status = "REV") +
                    (SELECT COUNT(*) FROM pecs p WHERE p.created_by = u.id AND p.status = "REV") +
                    (SELECT COUNT(*) FROM methodological_sheets_one mso WHERE mso.created_by = u.id AND mso.status = "REV") +
                    (SELECT COUNT(*) FROM methodological_sheets_two mst WHERE mst.created_by = u.id AND mst.status = "REV") +
                    (SELECT COUNT(*) FROM cultural_ensembles ce WHERE ce.created_by = u.id AND ce.status = "REV") +
                    (SELECT COUNT(*) FROM cultural_circulations cc WHERE cc.created_by = u.id AND cc.status = "REV") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs WHERE cs.created_by = u.id AND cs.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM inscriptions i WHERE i.created_by = u.id AND i.status = "APRO") +
                    (SELECT COUNT(*) FROM pecs p WHERE p.created_by = u.id AND p.status = "APRO") +
                    (SELECT COUNT(*) FROM methodological_sheets_one mso WHERE mso.created_by = u.id AND mso.status = "APRO") +
                    (SELECT COUNT(*) FROM methodological_sheets_two mst WHERE mst.created_by = u.id AND mst.status = "APRO") +
                    (SELECT COUNT(*) FROM cultural_ensembles ce WHERE ce.created_by = u.id AND ce.status = "APRO") +
                    (SELECT COUNT(*) FROM cultural_circulations cc WHERE cc.created_by = u.id AND cc.status = "APRO") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs WHERE cs.created_by = u.id AND cs.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 16
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
        $views= "DROP VIEW get_instructor_revisions;";
        DB::unprepared($views);
    }
};
