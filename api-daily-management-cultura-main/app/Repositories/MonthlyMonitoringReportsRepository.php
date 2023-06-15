<?php

namespace App\Repositories;

use App\Http\Resources\V1\MonthlyMonitoringReportsCollection;
use App\Models\MonthlyMonitoringReports;
use Illuminate\Http\Request;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Support\Facades\DB;

class MonthlyMonitoringReportsRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new MonthlyMonitoringReports();
    }

    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();
        $user_id = $this->getIdUserAuth();

        $paginate = config('global.paginate');

        $query = $this->model->query()->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC");

        if ($rol_id == config('roles.direccion')) {
            $query->where('direccion_id', $user_id);
        }
        if ($rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_al_seguimiento_monitoreo')) {
            $query->where('created_by', $user_id);
        }
        if ($rol_id == config('roles.direccion') || $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')) {
            $query->whereHas('user', function ($query) {
                $query->whereHas('profile', function ($profile) {
                    $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                });
            });
        }

        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_monthlyMonitoringReports');
        session()->put('count_page_monthlyMonitoringReports', ceil($query->get()->count()/$paginate));

        return new MonthlyMonitoringReportsCollection($query->simplePaginate($paginate));

    }

    public function create(Request $request)
    {
        $report = $this->model;
        $report->created_by = $this->getIdUserAuth();
        $report->consecutive = $request->consecutive;
        $report->description = $request->description;
        $report->direccion_id = config('user_default.director');
        $report->date = $request->date;
        $save = $report->save();

        if ($save) {
            $handle_1 = $this->send_file($request, 'file', 'monthly_monitoring_reports', $report->id);
            $report->update(['file' => $handle_1['response']['payload']]);
            $save &= $handle_1['response']['success'];
            DB::update(DB::RAW("UPDATE monthly_monitoring_reports SET consecutive = CONCAT('MR', id) WHERE id=$report->id"));

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
            $handle_1 = $this->update_file($request, 'file', 'monthly_monitoring_reports', $report->id, $report->file);

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
