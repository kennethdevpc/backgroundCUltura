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
            CREATE OR REPLACE VIEW get_apoyo_supervision_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*) FROM strengthening_super_mons_insts smi WHERE smi.created_by = u.id AND smi.status = "ENREV") +
                    (SELECT COUNT(*) FROM strengthening_super_mangs ssm WHERE ssm.created_by = u.id AND ssm.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*) FROM strengthening_super_mons_insts smi WHERE smi.created_by = u.id AND smi.status = "REC") +
                    (SELECT COUNT(*) FROM strengthening_super_mangs ssm WHERE ssm.created_by = u.id AND ssm.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*) FROM strengthening_super_mons_insts smi WHERE smi.created_by = u.id AND smi.status = "REV") +
                    (SELECT COUNT(*) FROM strengthening_super_mangs ssm WHERE ssm.created_by = u.id AND ssm.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*) FROM strengthening_super_mons_insts smi WHERE smi.created_by = u.id AND smi.status = "APRO") +
                    (SELECT COUNT(*) FROM strengthening_super_mangs ssm WHERE ssm.created_by = u.id AND ssm.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 11
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
        $views= "DROP VIEW get_apoyo_supervision_revisions;";
        DB::unprepared($views);
    }
};
