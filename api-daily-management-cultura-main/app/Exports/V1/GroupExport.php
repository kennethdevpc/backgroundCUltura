<?php

namespace App\Exports\V1;

use App\Models\Group;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class GroupExport implements FromView
{
    protected $data;
    protected $model;

    public function __construct($data)
    {
        $this->data = $data;
        $this->model = new Group();
    }

    public function view(): View
    {
        $results = $this->model->with('user')->get();

        return view('exports.groups', [
            'groups' => $results,
        ]);
    }
}
