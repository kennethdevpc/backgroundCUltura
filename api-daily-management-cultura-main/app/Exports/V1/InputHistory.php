<?php

namespace App\Exports\V1;

use App\Models\User;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InputHistory implements FromView
{
    public $datos;

    public function __construct($datos)
    {
        //Log::debug($datos);
        $this->datos = $datos;
    }

    public function view(): View
    {
        return view('exports.inputhistory', [
            'invoices' => User::with('loginaccess','profile','roles')->get(),
            'dateInicio' => $this->datos->date_start,
            'dateFin' => $this->datos->date_end,
        ]);
    }
}
