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
            CREATE OR REPLACE VIEW get_apoyo_seguimiento_monitoreo_re_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM inscriptions i LEFT JOIN profiles p ON p.user_id = i.created_by WHERE i.user_review_support_follow_id = u.id AND i.status = "ENREV") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE bi.user_review_support_follow_id  = u.id AND bi.status = "ENREV" AND bi.binnacle_id = "JP") +
                    (SELECT COUNT(*) FROM cultural_ensembles ce LEFT JOIN profiles p ON p.user_id = ce.created_by WHERE ce.user_review_support_follow_id  = u.id AND ce.status = "ENREV") +
                    (SELECT COUNT(*) FROM cultural_circulations ci LEFT JOIN profiles p ON p.user_id = ci.created_by WHERE ci.user_review_support_follow_id = u.id AND ci.status = "ENREV") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs LEFT JOIN profiles p ON p.user_id = cs.created_by WHERE cs.user_review_support_follow_id  = u.id AND cs.status = "ENREV") +
                    (SELECT COUNT(*) FROM binnacle_cultural_show bcs LEFT JOIN profiles p ON p.user_id = bcs.created_by WHERE bcs.user_review_support_follow_id = u.id AND bcs.status = "ENREV") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE bi.user_review_support_follow_id = u.id AND bi.status = "ENREV" AND bi.binnacle_id = "AC")
                        AS en_revision,
                    (SELECT COUNT(*) FROM inscriptions i LEFT JOIN profiles p ON p.user_id = i.created_by WHERE i.user_review_support_follow_id = u.id AND i.status = "REC") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE bi.user_review_support_follow_id = u.id AND bi.status = "REC" AND bi.binnacle_id = "JP") +
                    (SELECT COUNT(*) FROM cultural_ensembles ce LEFT JOIN profiles p ON p.user_id = ce.created_by WHERE ce.user_review_support_follow_id = u.id AND ce.status = "REC") +
                    (SELECT COUNT(*) FROM cultural_circulations ci LEFT JOIN profiles p ON p.user_id = ci.created_by WHERE ci.user_review_support_follow_id = u.id AND ci.status = "REC") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs LEFT JOIN profiles p ON p.user_id = cs.created_by WHERE cs.user_review_support_follow_id = u.id AND cs.status = "REC") +
                    (SELECT COUNT(*) FROM binnacle_cultural_show bcs LEFT JOIN profiles p ON p.user_id = bcs.created_by WHERE bcs.user_review_support_follow_id = u.id AND bcs.status = "REC") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE bi.user_review_support_follow_id = u.id AND bi.status = "REC" AND bi.binnacle_id = "AC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM inscriptions i LEFT JOIN profiles p ON p.user_id = i.created_by WHERE i.user_review_support_follow_id = u.id AND i.status = "REV") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE bi.user_review_support_follow_id = u.id AND bi.status = "REV" AND bi.binnacle_id = "JP") +
                    (SELECT COUNT(*) FROM cultural_ensembles ce LEFT JOIN profiles p ON p.user_id = ce.created_by WHERE ce.user_review_support_follow_id = u.id AND ce.status = "REV") +
                    (SELECT COUNT(*) FROM cultural_circulations ci LEFT JOIN profiles p ON p.user_id = ci.created_by WHERE ci.user_review_support_follow_id  = u.id AND ci.status = "REV") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs LEFT JOIN profiles p ON p.user_id = cs.created_by WHERE cs.user_review_support_follow_id = u.id AND cs.status = "REV") +
                    (SELECT COUNT(*) FROM binnacle_cultural_show bcs LEFT JOIN profiles p ON p.user_id = bcs.created_by WHERE bcs.user_review_support_follow_id = u.id AND bcs.status = "REV") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE bi.user_review_support_follow_id = u.id AND bi.status = "REV" AND bi.binnacle_id = "AC")
                        AS revisadas,
                    (SELECT COUNT(*) FROM inscriptions i LEFT JOIN profiles p ON p.user_id = i.created_by WHERE i.user_review_support_follow_id = u.id AND i.status = "APRO") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE bi.user_review_support_follow_id = u.id AND bi.status = "APRO" AND bi.binnacle_id = "JP") +
                    (SELECT COUNT(*) FROM cultural_ensembles ce LEFT JOIN profiles p ON p.user_id = ce.created_by WHERE ce.user_review_support_follow_id = u.id AND ce.status = "APRO") +
                    (SELECT COUNT(*) FROM cultural_circulations ci LEFT JOIN profiles p ON p.user_id = ci.created_by WHERE ci.user_review_support_follow_id = u.id AND ci.status = "APRO") +
                    (SELECT COUNT(*) FROM cultural_seedbeds cs LEFT JOIN profiles p ON p.user_id = cs.created_by WHERE cs.user_review_support_follow_id = u.id AND cs.status = "APRO") +
                    (SELECT COUNT(*) FROM binnacle_cultural_show bcs LEFT JOIN profiles p ON p.user_id = bcs.created_by WHERE bcs.user_review_support_follow_id = u.id AND bcs.status = "APRO") +
                    (SELECT COUNT(*) FROM binnacles bi LEFT JOIN profiles p ON p.user_id = bi.created_by WHERE bi.user_review_support_follow_id = u.id AND bi.status = "APRO" AND bi.binnacle_id = "AC")
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
        $views= "DROP VIEW get_apoyo_seguimiento_monitoreo_re_revisions;";
        DB::unprepared($views);
    }
};
