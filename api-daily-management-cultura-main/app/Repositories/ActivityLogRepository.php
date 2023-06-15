<?php

namespace App\Repositories;

use App\Http\Resources\V1\ActivityLogCollection;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use App\Traits\FunctionGeneralTrait;

class ActivityLogRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new ActivityLog();
    }

    public function getAll()
    {
        $activityLogs = $this->model->query();
        $paginate = config('global.paginate');

        session()->forget('count_page_activityLogs');
        session()->put('count_page_activityLogs', ceil($activityLogs->get()->count()/$paginate));

        return new ActivityLogCollection($activityLogs->simplePaginate($paginate));
    }

}
