<?php

namespace App\Traits;

use App\Models\Profile;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

trait FunctionGeneralTrait
{

    private function is_filled($data): bool
    {
        $is_fill = true;
        $props = get_object_vars($data);
        foreach ($props as $prop) {
            if ($prop == '') {
                $is_fill = false;
            }
        }
        return $is_fill;
    }

    public function validatorMessage($data, $validate, $messages, $attrs)
    {
        $validator = $this->validator((array) $data, $validate, $messages, $attrs);

        if ($validator->fails()) {
            return $validator;
        }
    }

    function validator($data, $validation, $messages, $attrs): \Illuminate\Validation\Validator
    {
        $validator = Validator::make($data, $validation, $messages, $attrs);
        return $validator;
    }

    public function getDate()
    {
        return  Carbon::now();
    }
    public function control_data($data, $action)
    {
        // Declaramos varibales temporales
        $data_original = $data->getOriginal();
        $data_change = $data->getChanges();
        // Quitamos las fechas
        unset($data_original['created_at'], $data_original['updated_at'], $data_original['deleted_at']);
        unset($data_change['created_at'], $data_change['updated_at'], $data_change['deleted_at']);
        // Guardamos la data en la BD
        $data->control_data()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'data_original' => $data_original,
            'data_change' => $data_change
        ]);
    }
    //Muestra el valor de la abreviatura que se realizo en selectDefault
    // Se pasa el dato y el nombre de la propieda de la que pertenece
    public function data($data, $type)
    {
        $selects = config('selectsDefault.' . $type);
        $text = '';
        foreach ($selects as $value) {
            if ($value['value'] == $data) {
                $text = $value['label'];
                break;
            }
        }
        return  $text;
    }
    // Trae el valor de la abreviatura que se realizo en selectDefault
    // Se pasa el dato y el nombre de la propieda de la que pertenece
    public function dataFilter($data, $type)
    {
        $selects = config('selectsDefault.' . $type);
        $text = '';
        foreach ($selects as $value) {
            if ($value['label'] == $data) {
                $text = $value['value'];
                break;
            }
        }
        return  $text;
    }
    // Va a mostrar los values y labels que se encuentran en el archivo de configuracion
    // se pasa el nombre de la propiedad
    public function dataSelect($type)
    {
        $selects = config('selectsDefault.' . $type);
        // Arreglo para almacenar los valores "label" y "value"
        $data = [];
        // Recorremos el arreglo original y almacenamos solo los valores "label" y "value"
        foreach ($selects as $placeType) {
            $data[] = [
                'label' => $placeType['label'],
                'value' => $placeType['value']
            ];
        }
        return  $data;
    }
    // Muestra el valor de la abreviatura que se realizo en selectDefault en el informe
    // Se pasa el value y el nombre de la propieda de la que pertenece
    public function dataExport($data, $type)
    {
        $selects = config('selectsDefaultExport.' . $type);
        return $selects[$data] ?? '';
    }
    // Separa las abreviaturas por comas y se trae el valor segun esta en selectDefault
    // Se pasa el dato y el nombre de la propiedad a la que pertenece
    public function split($data, $type)
    {
        $array = explode(',', $data);
        $results = "";
        foreach ($array as $value) {
            $results .= $this->data($value, $type);
            if ($value !== end($array)) {
                $results .= ' - ';
            }
        }
        return $results;
    }
    //Muestra concatenado los valores de una relación de uno a muchos

    public function printValueRelations($data)
    {
        $text = '';
        foreach ($data as $value) {
            $text .= $value['name'] . ', ';
        }

        return substr($text, 0, -1);
    }

    /**
     * Funcion para generar la numero de la ficha del formulario
     */
    public function generateDataSheet($date)
    {
        $carbon = Carbon::create($date);
        $dateIni = $carbon->firstOfMonth()->toDateString();
        $dateFin = $carbon->endOfMonth()->toDateString();
        $countSheetPerMonth = $this->model->whereBetween('date_ini', [$dateIni, $dateFin])->get()->count();
        $datasheet = 'Ficha ' . ($countSheetPerMonth + 1) . ' - ' . $carbon->monthName;

        return $datasheet;
    }
    /**
     * Función que devuelve, los perfiles que están asociados a un nac y que sean monitores o gestoress de igualmente los roles
     */
    public function getRolUserForNac($nac_id)
    {
        $roles = ['monitor_cultural', 'gestores_culturales'];

        $user_assistants = Profile::query()->whereHas('role', function ($query) use ($roles) {
            $query->whereIn('slug', $roles);
        })->where('nac_id', $nac_id)->select('id as profile_id', 'user_id as id', 'document_number', 'contractor_full_name as monitor_fullname', 'role_id', 'nac_id')
            ->get();


        $roles = Role::query()->whereHas('profile', function ($query) use ($nac_id) {
            $query->where('nac_id', $nac_id);
        })->select('roles.id as value', 'roles.name as label')->without('permissions')->get();

        return response()->json(
            [
                'assistants' => $user_assistants,
                'roles' => $roles
            ]

        );
    }


    /**
     * Función que devuelve, los perfiles que están asociados a un nac y que sean monitores o gestoress de igualmente los roles
     */
    public function getUsers($roles)
    {
        // $roles = ['monitor_cultural', 'gestores_culturales'];

        $user_assistants = Profile::query()->whereHas('role', function ($query) use ($roles) {
            $query->whereIn('id', $roles);
        })
            ->select('id as profile_id', 'user_id as id', 'document_number', 'contractor_full_name as monitor_fullname', 'role_id', 'nac_id')
            ->get();

        return response()->json($user_assistants);
    }
}
