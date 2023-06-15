<?php

namespace App\Exports\V1;

use App\Models\PsychopedagogicalLogbooks\PsychopedagogicalLogbook;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;

class PsychopedagogicalLogBooksExport implements FromView
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $model;

    public function __construct($data)
    {
        $this->data = $data;
        $this->model = new PsychopedagogicalLogbook();
    }


    public function view(): View
    {
        set_time_limit(0);

        $query = $this->model->query();

        $psychopedagogicalLogBooks = $query->with('user', 'monitor', 'nac', 'addedWizards', 'assistanceMonitors')
            ->whereNotIn('user_id', [1,2])
            ->whereHas('user', function($user){
                $user->where('deleted_at', null);
            })->get();

        if ($this->data['date_start'] != null && $this->data['date_end'] == null) {
            $psychopedagogicalLogBooks = $query->with('user', 'monitor', 'nac', 'addedWizards', 'assistanceMonitors')
            ->where('created_at', $this->data['date_start'])
            ->whereNotIn('user_id', [1,2])
            ->whereHas('user', function($user){
                $user->where('deleted_at', null);
            })->get();
        }

        if ($this->data['date_start'] == null && $this->data['date_end'] != null) {
            $psychopedagogicalLogBooks = $query->with('user', 'monitor', 'nac', 'addedWizards', 'assistanceMonitors')
                ->where('created_at', '<=', $this->data['date_end'])
                ->whereNotIn('user_id', [1,2])
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }

        if ($this->data['date_start'] != null && $this->data['date_end'] != null) {
            $psychopedagogicalLogBooks = $query->with('user', 'monitor', 'nac', 'addedWizards', 'assistanceMonitors')
                ->where('created_at', '>=', $this->data['date_start'])
                ->where('created_at', '<=', $this->data['date_end'])
                ->whereNotIn('user_id', [1,2])
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }

        return view('exports.sychopedagogicalLogBooks', [
            'data' => $psychopedagogicalLogBooks,
            'trait' => new PsychopedagogicalLogBooksExport(null),
        ]);
    }

}
