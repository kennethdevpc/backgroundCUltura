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
            CREATE OR REPLACE VIEW get_lider_instructor_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM cultural_ensembles c LEFT JOIN profiles p ON p.user_id = c.created_by WHERE p.instructor_leader_id = u.id AND c.status = "ENREV") +
                    (SELECT COUNT(*) FROM cultural_circulations cc LEFT JOIN profiles p ON p.user_id = cc.created_by WHERE p.instructor_leader_id = u.id AND cc.status = "ENREV") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs LEFT JOIN profiles p ON p.user_id = cs.created_by WHERE p.instructor_leader_id = u.id AND cs.status = "ENREV") +
                    (SELECT COUNT(*) FROM binnacle_cultural_show bc LEFT JOIN profiles p ON p.user_id = bc.created_by WHERE p.instructor_leader_id = u.id AND bc.status = "ENREV") +
                    (SELECT COUNT(*) FROM pecs pe LEFT JOIN profiles p ON p.user_id = pe.created_by WHERE p.instructor_leader_id = u.id AND pe.status = "ENREV") +
                    (SELECT COUNT(*) FROM methodological_sheets_one mso LEFT JOIN profiles p ON p.user_id = mso.created_by WHERE p.instructor_leader_id = u.id AND mso.status = "ENREV") +
                    (SELECT COUNT(*) FROM methodological_sheets_two mst LEFT JOIN profiles p ON p.user_id = mst.created_by WHERE p.instructor_leader_id = u.id AND mst.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*) FROM cultural_ensembles c LEFT JOIN profiles p ON p.user_id = c.created_by WHERE p.instructor_leader_id = u.id AND c.status = "REC") +
                    (SELECT COUNT(*) FROM cultural_circulations cc LEFT JOIN profiles p ON p.user_id = cc.created_by WHERE p.instructor_leader_id = u.id AND cc.status = "REC") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs LEFT JOIN profiles p ON p.user_id = cs.created_by WHERE p.instructor_leader_id = u.id AND cs.status = "REC") +
                    (SELECT COUNT(*) FROM binnacle_cultural_show bc LEFT JOIN profiles p ON p.user_id = bc.created_by WHERE p.instructor_leader_id = u.id AND bc.status = "REC") +
                    (SELECT COUNT(*) FROM pecs pe LEFT JOIN profiles p ON p.user_id = pe.created_by WHERE p.instructor_leader_id = u.id AND pe.status = "REC") +
                    (SELECT COUNT(*) FROM methodological_sheets_one mso LEFT JOIN profiles p ON p.user_id = mso.created_by WHERE p.instructor_leader_id = u.id AND mso.status = "REC") +
                    (SELECT COUNT(*) FROM methodological_sheets_two mst LEFT JOIN profiles p ON p.user_id = mst.created_by WHERE p.instructor_leader_id = u.id AND mst.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM cultural_ensembles c LEFT JOIN profiles p ON p.user_id = c.created_by WHERE p.instructor_leader_id = u.id AND c.status = "REV") +
                    (SELECT COUNT(*) FROM cultural_circulations cc LEFT JOIN profiles p ON p.user_id = cc.created_by WHERE p.instructor_leader_id = u.id AND cc.status = "REV") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs LEFT JOIN profiles p ON p.user_id = cs.created_by WHERE p.instructor_leader_id = u.id AND cs.status = "REV") +
                    (SELECT COUNT(*) FROM binnacle_cultural_show bc LEFT JOIN profiles p ON p.user_id = bc.created_by WHERE p.instructor_leader_id = u.id AND bc.status = "REV") +
                    (SELECT COUNT(*) FROM pecs pe LEFT JOIN profiles p ON p.user_id = pe.created_by WHERE p.instructor_leader_id = u.id AND pe.status = "REV") +
                    (SELECT COUNT(*) FROM methodological_sheets_one mso LEFT JOIN profiles p ON p.user_id = mso.created_by WHERE p.instructor_leader_id = u.id AND mso.status = "REV") +
                    (SELECT COUNT(*) FROM methodological_sheets_two mst LEFT JOIN profiles p ON p.user_id = mst.created_by WHERE p.instructor_leader_id = u.id AND mst.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM cultural_ensembles c LEFT JOIN profiles p ON p.user_id = c.created_by WHERE p.instructor_leader_id = u.id AND c.status = "APRO") +
                    (SELECT COUNT(*) FROM cultural_circulations cc LEFT JOIN profiles p ON p.user_id = cc.created_by WHERE p.instructor_leader_id = u.id AND cc.status = "APRO") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs LEFT JOIN profiles p ON p.user_id = cs.created_by WHERE p.instructor_leader_id = u.id AND cs.status = "APRO") +
                    (SELECT COUNT(*) FROM binnacle_cultural_show bc LEFT JOIN profiles p ON p.user_id = bc.created_by WHERE p.instructor_leader_id = u.id AND bc.status = "APRO") +
                    (SELECT COUNT(*) FROM pecs pe LEFT JOIN profiles p ON p.user_id = pe.created_by WHERE p.instructor_leader_id = u.id AND pe.status = "APRO") +
                    (SELECT COUNT(*) FROM methodological_sheets_one mso LEFT JOIN profiles p ON p.user_id = mso.created_by WHERE p.instructor_leader_id = u.id AND mso.status = "APRO") +
                    (SELECT COUNT(*) FROM methodological_sheets_two mst LEFT JOIN profiles p ON p.user_id = mst.created_by WHERE p.instructor_leader_id = u.id AND mst.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 18
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
        $views= "DROP VIEW get_lider_instructor_revisions;";
        DB::unprepared($views);
    }
};
