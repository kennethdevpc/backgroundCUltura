<?php

namespace App\Repositories;

use App\Http\Resources\V1\MonitoringReportsCollection;
use App\Models\MonitoringReports;
use Illuminate\Http\Request;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Support\Facades\DB;

class MonitoringReportRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new MonitoringReports();
    }

    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();
        $user_id = $this->getIdUserAuth();

        $paginate = config('global.paginate');

        $query = $this->model->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
            ->orderBy('id', 'DESC');

        if ($rol_id == config('roles.coordinador_seguimiento')) {
            $query->where('monitoring_coordinator_id', $user_id);
        }
        if ($rol_id == config('roles.apoyo_al_seguimiento_monitoreo')) {
            $query->where('created_by', $user_id);
        }
        if ($rol_id == config('roles.direccion') || $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')) {
            $reports = $query->whereHas('user', function ($query) {
                $query->whereHas('profile', function ($profile) {
                    $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                });
            });
        }

        // Aplicar filtros adicionales desde la URL
        // $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_monitoringReports');
        session()->put('count_page_monitoringReports', ceil($query->get()->count()/$paginate));

        return new MonitoringReportsCollection($query->simplePaginate($paginate));

        return new MonitoringReportsCollection($reports);
    }

    public function create(Request $request)
    {
        $report = $this->model;
        $report->created_by = $this->getIdUserAuth();
        $report->consecutive = $request->consecutive;
        $report->description = $request->description;
        $report->date = $request->date;
        $report->monitoring_coordinator_id = config('user_default.monitoring_coordinator');
        $save = $report->save();
        $id = $this->model->id;
        DB::update(DB::RAW("UPDATE monitoring_reports SET consecutive = CONCAT('MR', id) WHERE id=$id"));
        if ($save) {
            $handle_1 = $this->send_file($request, 'file', 'monitoring_reports', $report->id);
            $report->update(['file' => $handle_1['response']['payload']]);
            $save &= $handle_1['response']['success'];
        }

        // Guardamos en DataModel
        $this->control_data($report, 'store');

        return $report;
    }

    public function update(Request $request, $data, $id)
    {

        $report = $this->model->find($id);
        $report->consecutive = $request->consecutive;
        $report->date = $request->date;
        $report->description = $request->description;
        if ($report->status == 'REC')
            $report->status = 'ENREV';

        if ($request->hasFile('file')) {
            $handle_1 = $this->update_file($request, 'file', 'monitoring_reports', $report->id, $report->file);

            $report->update(['file' => $handle_1['response']['payload']]);
        }

        $report->save();

        // Guardamos en DataModel
        $this->control_data($report, 'update');

        return $report;
    }

    public function findById($id)
    {
        $find = $this->model->find($id);
        return $find;
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    function getValidate($data, $method)
    {
        $validate = [
            'consecutive' => 'bail|required',
            'description' =>  'required|max:3500',
            'file' => $method != 'update' ? 'bail|required|max:' . (500 * 1049000) : 'bail',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
        ];

        $attrs = [
            'consecutive' => 'Consecutivo',
            'description' => 'Descripcion',
            'file' => 'Documento',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }
}
