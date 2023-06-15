<?php

namespace App\Exports\V1\Revisions;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class UserEmbajadorRevisionExport implements FromView, WithTitle
{
    use Exportable, FunctionGeneralTrait;

    private $sheetName;

    public function __construct()
    {
        $this->sheetName = $this->title();
    }

    public function title(): string
    {
        return 'EMBAJADORES';
    }

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');

        $data = DB::table('get_embajador_revisions')->get();

        return view('exports.user_revision', [
            'data' => $data,
        ]);

    }
}
