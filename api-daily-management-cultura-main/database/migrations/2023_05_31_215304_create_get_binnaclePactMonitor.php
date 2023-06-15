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
        $views = "
            CREATE OR REPLACE VIEW get_binnaclePactMonitor AS
                SELECT *
                FROM (
                    SELECT
                        b.consecutive,
                        u.name AS name_user,
                        p.document_number AS doc_number_user,
                        r.name AS name_role,
                        n.name AS name_nac,
                        b.activity_name,
                        b.activity_date,
                        DATE_FORMAT(b.start_time, '%k:%i') AS start_time,
                        DATE_FORMAT(b.final_hour, '%k:%i') AS final_hour,
                        b.place,
                        CONCAT(pe.consecutive, ' - ', pe.activity_date, ' - ', DATE_FORMAT(pe.start_time, '%k:%i'), ' - ', DATE_FORMAT(pe.final_hour, '%k:%i')) AS data_pec,
                        CONCAT(peda.consecutive, ' - ', peda.activity_date) AS data_pedagogical,
                        n2.name AS name_nac_binnacle,
                        e.name AS name_expertise,
                        cr.name AS name_cultural,
                        b.experiential_objective,
                        b.lineament_id,
                        o.name AS name_orientation,
                        b.goals_met,
                        b.explain_goals_met,
                        b.start_activity,
                        b.activity_development,
                        b.end_of_activity,
                        b.observations_activity,
                        g.name AS name_group,
                        benef_count.beneficiary_count,
                        DATE_FORMAT(b.created_at, '%Y-%m-%d %H:%i:%s') AS created_at,
                        b.status
                    FROM
                        binnacles b
                        INNER JOIN users u ON u.id = b.created_by
                        INNER JOIN profiles p ON p.user_id = b.created_by
                        INNER JOIN roles r ON r.id = p.role_id
                        INNER JOIN nacs n ON n.id = p.nac_id
                        INNER JOIN pecs pe ON pe.id = b.pec_id
                        INNER JOIN pedagogicals peda ON peda.id = b.pedagogical_id
                        INNER JOIN nacs n2 ON n2.id = b.nac_id
                        INNER JOIN expertises e ON e.id = b.expertise_id
                        INNER JOIN cultural_rights cr ON cr.id = b.cultural_right_id
                        INNER JOIN orientations o ON o.id = b.orientation_id
                        INNER JOIN beneficiary_binnacle be_binna ON be_binna.binnacle_id = b.id
                        INNER JOIN beneficiaries bene ON bene.id = be_binna.beneficiary_id
                        INNER JOIN `groups` g ON g.id = bene.group_id
                        INNER JOIN (
                            SELECT
                                binnacle_id,
                                COUNT(*) AS beneficiary_count
                            FROM
                                beneficiary_binnacle
                            GROUP BY
                                binnacle_id
                        ) benef_count ON benef_count.binnacle_id = b.id
                    WHERE
                        b.created_by NOT IN (1, 2)
                        AND b.binnacle_id = 'JP'
                        AND b.deleted_at IS NULL
                ) AS subquery
                GROUP BY
                    consecutive;
        ";
        DB::unprepared($views);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $views= "DROP VIEW get_binnaclePactMonitor;";
        DB::unprepared($views);
    }
};
