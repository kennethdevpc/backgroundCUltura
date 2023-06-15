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
            CREATE OR REPLACE VIEW get_revision_apoyo_metodologico_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM methodological_instructions mi LEFT JOIN profiles p ON p.user_id = mi.created_by WHERE p.methodological_support_id = u.id AND mi.status = "ENREV") +
                    (SELECT COUNT(*) FROM dialogue_tables dt LEFT JOIN profiles p ON p.user_id = dt.user_id WHERE p.methodological_support_id = u.id AND dt.status = "ENREV") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE p.methodological_support_id = u.id AND bi.status = "ENREV" AND bi.binnacle_id = "AC") +
                    (SELECT COUNT(*) FROM manager_monitorings mm LEFT JOIN profiles p ON p.user_id = mm.user_id WHERE p.methodological_support_id = u.id AND mm.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*) FROM methodological_instructions mi LEFT JOIN profiles p ON p.user_id = mi.created_by WHERE p.methodological_support_id = u.id AND mi.status = "REC") +
                    (SELECT COUNT(*) FROM dialogue_tables dt LEFT JOIN profiles p ON p.user_id = dt.user_id WHERE p.methodological_support_id = u.id AND dt.status = "REC") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE p.methodological_support_id = u.id AND bi.status = "REC" AND bi.binnacle_id = "AC") +
                    (SELECT COUNT(*) FROM manager_monitorings mm LEFT JOIN profiles p ON p.user_id = mm.user_id WHERE p.methodological_support_id = u.id AND mm.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM methodological_instructions mi LEFT JOIN profiles p ON p.user_id = mi.created_by WHERE p.methodological_support_id = u.id AND mi.status = "REV") +
                    (SELECT COUNT(*) FROM dialogue_tables dt LEFT JOIN profiles p ON p.user_id = dt.user_id WHERE p.methodological_support_id = u.id AND dt.status = "REV") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE p.methodological_support_id = u.id AND bi.status = "REV" AND bi.binnacle_id = "AC") +
                    (SELECT COUNT(*) FROM manager_monitorings mm LEFT JOIN profiles p ON p.user_id = mm.user_id WHERE p.methodological_support_id = u.id AND mm.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM methodological_instructions mi LEFT JOIN profiles p ON p.user_id = mi.created_by WHERE p.methodological_support_id = u.id AND mi.status = "APRO") +
                    (SELECT COUNT(*) FROM dialogue_tables dt LEFT JOIN profiles p ON p.user_id = dt.user_id WHERE p.methodological_support_id = u.id AND dt.status = "APRO") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE p.methodological_support_id = u.id AND bi.status = "APRO" AND bi.binnacle_id = "AC") +
                    (SELECT COUNT(*) FROM manager_monitorings mm LEFT JOIN profiles p ON p.user_id = mm.user_id WHERE p.methodological_support_id = u.id AND mm.status = "APRO")
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
        $views= "DROP VIEW get_revision_apoyo_metodologico_revisions;";
        DB::unprepared($views);
    }
};
