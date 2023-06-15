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
            CREATE OR REPLACE VIEW get_revisiones_coordinadores_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM methodological_accompaniments ma WHERE ma.metho_coordinator_id = u.id AND ma.status = "ENREV") +
                    (SELECT COUNT(*) FROM methodological_strengthenings ms WHERE ms.metho_coordinator_id = u.id AND ms.status = "ENREV") +
                    (SELECT COUNT(*) FROM methodological_monitorings mm
                                                LEFT JOIN profiles p ON p.user_id = mm.created_by
                                            WHERE p.methodological_coordinator_id = u.id AND mm.status = "ENREV") +
                    (SELECT COUNT(*) FROM strengthening_of_monitorings sm
                                                LEFT JOIN profiles p ON p.user_id = sm.created_by
                                            WHERE p.monitoring_coordinator_id = u.id AND sm.status = "ENREV") +
                    (SELECT COUNT(*) FROM monitoring_reports mr
                                                LEFT JOIN profiles p ON p.user_id = mr.created_by
                                            WHERE p.monitoring_coordinator_id = u.id AND mr.status = "ENREV") +
                    (SELECT COUNT(*) FROM strengthening_super_mons_insts smi WHERE smi.supervised_user_full_name = u.id AND smi.status = "ENREV") +
                    (SELECT COUNT(*) FROM strengthening_super_mangs ssm WHERE ssm.super_coordinator_id = u.id AND ssm.status = "ENREV") +
                    (SELECT COUNT(*) FROM psychopedagogical_logbooks pl WHERE pl.user_psychoso_coordinator_id = u.id AND pl.status = "ENREV") +
                    (SELECT COUNT(*) FROM psychosocial_instructions ps WHERE ps.user_psychoso_coordinator_id = u.id AND ps.status = "ENREV") +
                    (SELECT COUNT(*) FROM parent_schools pch WHERE pch.user_psychoso_coordinator_id = u.id AND pch.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*) FROM methodological_accompaniments ma WHERE ma.metho_coordinator_id = u.id AND ma.status = "REC") +
                    (SELECT COUNT(*) FROM methodological_strengthenings ms WHERE ms.metho_coordinator_id = u.id AND ms.status = "REC") +
                    (SELECT COUNT(*) FROM methodological_monitorings mm
                                                LEFT JOIN profiles p ON p.user_id = mm.created_by
                                            WHERE p.methodological_coordinator_id = u.id AND mm.status = "REC") +
                    (SELECT COUNT(*) FROM strengthening_of_monitorings sm
                                                LEFT JOIN profiles p ON p.user_id = sm.created_by
                                            WHERE p.monitoring_coordinator_id = u.id AND sm.status = "REC") +
                    (SELECT COUNT(*) FROM monitoring_reports mr
                                                LEFT JOIN profiles p ON p.user_id = mr.created_by
                                            WHERE p.monitoring_coordinator_id = u.id AND mr.status = "REC") +
                    (SELECT COUNT(*) FROM strengthening_super_mons_insts smi WHERE smi.supervised_user_full_name = u.id AND smi.status = "REC") +
                    (SELECT COUNT(*) FROM strengthening_super_mangs ssm WHERE ssm.super_coordinator_id = u.id AND ssm.status = "REC") +
                    (SELECT COUNT(*) FROM psychopedagogical_logbooks pl WHERE pl.user_psychoso_coordinator_id = u.id AND pl.status = "REC") +
                    (SELECT COUNT(*) FROM psychosocial_instructions ps WHERE ps.user_psychoso_coordinator_id = u.id AND ps.status = "REC") +
                    (SELECT COUNT(*) FROM parent_schools pch WHERE pch.user_psychoso_coordinator_id = u.id AND pch.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM methodological_accompaniments ma WHERE ma.metho_coordinator_id = u.id AND ma.status = "REV") +
                    (SELECT COUNT(*) FROM methodological_strengthenings ms WHERE ms.metho_coordinator_id = u.id AND ms.status = "REV") +
                    (SELECT COUNT(*) FROM methodological_monitorings mm
                                                LEFT JOIN profiles p ON p.user_id = mm.created_by
                                            WHERE p.methodological_coordinator_id = u.id AND mm.status = "REV") +
                    (SELECT COUNT(*) FROM strengthening_of_monitorings sm
                                                LEFT JOIN profiles p ON p.user_id = sm.created_by
                                            WHERE p.monitoring_coordinator_id = u.id AND sm.status = "REV") +
                    (SELECT COUNT(*) FROM monitoring_reports mr
                                                LEFT JOIN profiles p ON p.user_id = mr.created_by
                                            WHERE p.monitoring_coordinator_id = u.id AND mr.status = "REV") +
                    (SELECT COUNT(*) FROM strengthening_super_mons_insts smi WHERE smi.supervised_user_full_name = u.id AND smi.status = "REV") +
                    (SELECT COUNT(*) FROM strengthening_super_mangs ssm WHERE ssm.super_coordinator_id = u.id AND ssm.status = "REV") +
                    (SELECT COUNT(*) FROM psychopedagogical_logbooks pl WHERE pl.user_psychoso_coordinator_id = u.id AND pl.status = "REV") +
                    (SELECT COUNT(*) FROM psychosocial_instructions ps WHERE ps.user_psychoso_coordinator_id = u.id AND ps.status = "REV") +
                    (SELECT COUNT(*) FROM parent_schools pch WHERE pch.user_psychoso_coordinator_id = u.id AND pch.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM methodological_accompaniments ma WHERE ma.metho_coordinator_id = u.id AND ma.status = "APRO") +
                    (SELECT COUNT(*) FROM methodological_strengthenings ms WHERE ms.metho_coordinator_id = u.id AND ms.status = "APRO") +
                    (SELECT COUNT(*) FROM methodological_monitorings mm
                                                LEFT JOIN profiles p ON p.user_id = mm.created_by
                                            WHERE p.methodological_coordinator_id = u.id AND mm.status = "APRO") +
                    (SELECT COUNT(*) FROM strengthening_of_monitorings sm
                                                LEFT JOIN profiles p ON p.user_id = sm.created_by
                                            WHERE p.monitoring_coordinator_id = u.id AND sm.status = "APRO") +
                    (SELECT COUNT(*) FROM monitoring_reports mr
                                                LEFT JOIN profiles p ON p.user_id = mr.created_by
                                            WHERE p.monitoring_coordinator_id = u.id AND mr.status = "APRO") +
                        (SELECT COUNT(*) FROM strengthening_super_mons_insts smi WHERE smi.supervised_user_full_name = u.id AND smi.status = "APRO") +
                    (SELECT COUNT(*) FROM strengthening_super_mangs ssm WHERE ssm.super_coordinator_id = u.id AND ssm.status = "APRO") +
                    (SELECT COUNT(*) FROM psychopedagogical_logbooks pl WHERE pl.user_psychoso_coordinator_id = u.id AND pl.status = "APRO") +
                    (SELECT COUNT(*) FROM psychosocial_instructions ps WHERE ps.user_psychoso_coordinator_id = u.id AND ps.status = "APRO") +
                    (SELECT COUNT(*) FROM parent_schools pch WHERE pch.user_psychoso_coordinator_id = u.id AND pch.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 5 OR
                    p.role_id = 9 OR
                    p.role_id = 10 OR
                    p.role_id = 19 OR
                    p.role_id = 21
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
        $views= "DROP VIEW get_revisiones_coordinadores_revisions;";
        DB::unprepared($views);
    }
};
