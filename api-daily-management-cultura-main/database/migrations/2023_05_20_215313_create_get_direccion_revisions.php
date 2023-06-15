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
            CREATE OR REPLACE VIEW get_direccion_revisions AS
                SELECT DISTINCT u.name AS name, r.name AS rol,
                    (SELECT COUNT(*)
                        FROM binnacle_territories bt
                            JOIN users ON users.id = bt.created_by
                            JOIN role_user ON role_user.user_id = users.id
                            JOIN roles ON roles.id = role_user.role_id
                        WHERE roles.slug IN ("coordinador_supervision")
                            AND bt.status = "ENREV") +
                    (SELECT COUNT(*) FROM monthly_monitoring_reports mmr WHERE mmr.direccion_id = u.id AND mmr.status = "ENREV")
                        AS en_revision,
                    (SELECT COUNT(*)
                        FROM binnacle_territories bt
                            JOIN users ON users.id = bt.created_by
                            JOIN role_user ON role_user.user_id = users.id
                            JOIN roles ON roles.id = role_user.role_id
                        WHERE roles.slug IN ("coordinador_supervision")
                            AND bt.status = "REC") +
                    (SELECT COUNT(*) FROM monthly_monitoring_reports mmr WHERE mmr.direccion_id = u.id AND mmr.status = "REC")
                        AS rechazadas,
                    (SELECT COUNT(*)
                        FROM binnacle_territories bt
                            JOIN users ON users.id = bt.created_by
                            JOIN role_user ON role_user.user_id = users.id
                            JOIN roles ON roles.id = role_user.role_id
                        WHERE roles.slug IN ("coordinador_supervision")
                            AND bt.status = "REV") +
                    (SELECT COUNT(*) FROM monthly_monitoring_reports mmr WHERE mmr.direccion_id = u.id AND mmr.status = "REV")
                        AS revisadas,
                    (SELECT COUNT(*)
                        FROM binnacle_territories bt
                            JOIN users ON users.id = bt.created_by
                            JOIN role_user ON role_user.user_id = users.id
                            JOIN roles ON roles.id = role_user.role_id
                        WHERE roles.slug IN ("coordinador_supervision")
                            AND bt.status = "APRO") +
                    (SELECT COUNT(*) FROM monthly_monitoring_reports mmr WHERE mmr.direccion_id = u.id AND mmr.status = "APRO")
                        AS aprobadas
                FROM users u
                    LEFT JOIN profiles p ON p.user_id = u.id
                    LEFT JOIN roles r ON r.id = p.role_id
                WHERE
                    p.role_id = 7
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
        $views= "DROP VIEW get_direccion_revisions;";
        DB::unprepared($views);
    }
};
