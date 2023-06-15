<?php

namespace App\Exports\V1;

use App\Exports\V1\Revisions\UserRevisaApoyoSeguiMonitoreoRevisionExport;
use App\Exports\V1\Revisions\UserApoyoSeguiMonitoreoRevisionExport;
use App\Exports\V1\Revisions\UserLiderMetodologicoRevisionExport;
use App\Exports\V1\Revisions\UserApoyoMetodologicoRevisionExport;
use App\Exports\V1\Revisions\UserRevisaApoyoMethoRevisionExport;
use App\Exports\V1\Revisions\UserLiderInstructorRevisionExport;
use App\Exports\V1\Revisions\UserSubDireccionRevisionExport;
use App\Exports\V1\Revisions\UserRevisaGestorRevisionExport;
use App\Exports\V1\Revisions\UserCoordinadorRevisionExport;
use App\Exports\V1\Revisions\UserApoyoPsicoRevisionExport;
use App\Exports\V1\Revisions\UserApoyoSuperRevisionExport;
use App\Exports\V1\Revisions\UserInstructorRevisionExport;
use App\Exports\V1\Revisions\UserEmbajadorRevisionExport;
use App\Exports\V1\Revisions\UserDireccionRevisionExport;
use App\Exports\V1\Revisions\UserMonitorRevisionExport;
use App\Exports\V1\Revisions\UserGestorRevisionExport;
use App\Exports\V1\Revisions\UserRevisionCoordinadorRevisionExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RevisionExportMultipleSheets implements WithMultipleSheets
{
    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[0] = new UserMonitorRevisionExport();
        $sheets[1] = new UserInstructorRevisionExport();
        $sheets[2] = new UserEmbajadorRevisionExport();
        $sheets[3] = new UserGestorRevisionExport();
        $sheets[4] = new UserApoyoMetodologicoRevisionExport();
        $sheets[5] = new UserLiderMetodologicoRevisionExport();
        $sheets[6] = new UserApoyoSeguiMonitoreoRevisionExport();
        $sheets[7] = new UserApoyoSuperRevisionExport();
        $sheets[8] = new UserApoyoPsicoRevisionExport();
        $sheets[9] = new UserCoordinadorRevisionExport();
        $sheets[10] = new UserDireccionRevisionExport();
        $sheets[11] = new UserSubDireccionRevisionExport();
        $sheets[12] = new UserLiderInstructorRevisionExport();
        $sheets[13] = new UserRevisaApoyoSeguiMonitoreoRevisionExport();
        $sheets[14] = new UserRevisaGestorRevisionExport();
        $sheets[15] = new UserRevisaApoyoMethoRevisionExport();
        $sheets[16] = new UserRevisionCoordinadorRevisionExport();

        return $sheets;
    }
}
