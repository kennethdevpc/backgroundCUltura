<?php

namespace App\Repositories;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\V1\PecCollection;
use App\Http\Resources\V1\PecResource;
use App\Models\Inscriptions\BeneficiaryPec;
use App\Models\Inscriptions\Pec;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use Illuminate\Support\Facades\Auth;
use App\Traits\FunctionGeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PecRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;
    function __construct()
    {
        $this->model = new Pec();
    }

    public function getAll()
    {
        try {
            $rol_id = Auth::user()->profile->role->id;
            $user_id = Auth::user()->id;
            $query = $this->model->query();

        // Ordenamiento por estado y por id
        $query->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")->orderBy('id', 'DESC');

        // Filtrado por rol de usuario
        switch ($rol_id) {
            case config('roles.monitor'):
            case config('roles.instructor'):
                $query->where('created_by', $user_id);
                break;
            case config('roles.gestor'):
                $query->where('user_review_manager_cultural_id', $user_id);
                break;
            case config('roles.lider_instructor'):
                $query->where('user_review_instructor_leader_id', $user_id);
                break;
            case config('roles.direccion'):
            case config('roles.secretaria_cultural'):
            case config('roles.apoyo_supervision'):
            case config('roles.coordinador_supervision'):
                $query->whereHas('user', function ($query) {
                    $query->whereHas('profile', function ($profile) {
                        $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                    });
                });
                break;
            case config('roles.root'):
            case config('roles.super_root'):
                // No se filtra por usuario
                break;
            default:
                // Rol de usuario no reconocido
                return response()->json(['error' => 'Rol de usuario no reconocido', 'success' => false], 404);
        }

            // Aplicar filtros adicionales desde la URL
            $query = $this->model->scopeFilterByUrl($query);

            // Calcular número de páginas para paginación
            session()->forget('count_page_users');
            session()->put('count_page_users', ceil($query->count() / config('global.paginate')));

            // Realizar paginación y retornar resultados como una PecCollection
            return new PecCollection($query->with(['neighborhood'])->simplePaginate(config('global.paginate')));

    } catch (\Exception $ex) {
        return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine() . ', archivo ' . $ex->getFile(), 'success' => false], 404);
    }
}


    //insercion de datos que llegan del mètodo al metodo store
    public function create($request)
    {
        $pec = $this->model;
        //insercion de imagenes en un acceso directo "images", creado en "public", la cual se encuentra en la carpeta store
        // $imagen1 = $request->place_image1->store('public/images');
        // $imagen2 = $request->place_image2->store('public/images');
        // //inserciòn de la url de la imagen en la tabla
        // $place_image1 = Storage::url($imagen1);
        // $place_image2 = Storage::url($imagen2);
        //crear registro en la tabla pec

        // $pecByActivityDate = Pec::query()->where('activity_date', $request->activity_date)->where('start_time', $request->start_time)->get()->first();

        $pecByActivityDate = Pec::query()->where('activity_date', $request->activity_date)
            ->where('start_time', $request->start_time)->where('created_by', Auth::id())->get()->first();

        if ($pecByActivityDate !== null) {
            return response()->json(['error' => "Ya existe una PEC con la fecha y la hora inicial de actividad: $request->activity_date,  $request->start_time", 'success' => false,], Response::HTTP_CONFLICT);
        }

        $pec->activity_date = $request->activity_date;
        $pec->created_by = $request->user_id;
        $pec->nac_id = $request->nac_id;
        $pec->consecutive = $request->consecutive;
        $pec->neighborhood_id = $request->neighborhood_id;
        $pec->place = $request->place;
        $pec->place_address = $request->place_address;
        $pec->start_time = $request->start_time;
        $pec->final_hour = $request->final_hour;
        $pec->place_description = $request->place_description;
        $pec->place_type = $request->place_type;
        $pec->other_place_type = $request->other_place_type;
        $save = $pec->save();

        if ($save) {
            $handle_1 = $this->send_file($request, 'place_image1', 'pecs', $pec->id);
            $pec->update(['place_image1' => $handle_1['response']['payload']]);
            $save &= $handle_1['response']['success'];
            $handle_2 = $this->send_file($request, 'place_image2', 'pecs', $pec->id);
            $pec->update(['place_image2' => $handle_2['response']['payload']]);
            $save &= $handle_2['response']['success'];
            DB::update(DB::RAW("UPDATE pecs SET consecutive = CONCAT('PC', id) WHERE id=$pec->id"));
        }

        // Guardamos en DataModel
        $this->control_data($pec, 'store');

        // insercion de los beneficiarios seleccionados por pec creado, en la tabla pivote beneficiario_pec
        if ($this->toBoolean($save)) {
            $array = explode(",", $request->beneficiary_list);
            foreach ($array as $value) {
                BeneficiaryPec::create([
                    'pec_id' => $pec->id,
                    'beneficiary_id' => $value,
                ]);
            }
            return response()->json(['items' => 'Datos almacenado correctamente', 'success' => true,]);
        }

        return response()->json(['error' => 'Ocurrió un problema en el almacenamiento', 'success' => false,], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function findById($id)
    {
        return new PecResource($this->model->find($id));
    }

    public function getPecsCreatedBy($createdBy)
    {
        $data = $this
            ->model
            ->select(DB::raw(("CONCAT(consecutive, ' ', activity_date, ' ', start_time, ' - ', final_hour) as label")), 'id as value', 'place_address as address')
            ->where('created_by', '=', $createdBy)
            ->where('status', '=', 'APRO')
            ->get();
        return $data;
    }

    //recibe un registro por su id para actualizar
    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $pec = $this->model->find($id);
            // Carbon::createFromFormat('d/m/Y', $request->activity_date)->format('Y/m/d')
            // actualiza un pec por su id
            $activity_date = $request->activity_date;
            $pec->nac_id = $request->nac_id;
            $pec->neighborhood_id = $request->neighborhood_id;
            $pec->place = $request->place;
            $pec->activity_date = $activity_date;
            $pec->place_address = $request->place_address;
            $pec->start_time = $request->start_time;
            $pec->final_hour = $request->final_hour;
            $pec->place_description = $request->place_description;
            $pec->place_type = $request->place_type;
            $pec->other_place_type = $request->other_place_type;

            if ($pec->status == 'REC') {
                $rol_id = $this->getIdRolUserAuth();
                if ($rol_id == config('roles.monitor') || $rol_id == config('roles.instructor')) {
                    $pec->status = 'ENREV';
                }
            }

            $save = $pec->save();

            if ($save) {
                if ($request->hasFile('place_image1')) {
                    $handle_1 = $this->update_file($request, 'place_image1', 'pecs', $pec->id, $pec->place_image1);
                    $pec->update(['place_image1' => $handle_1['response']['payload']]);
                    $save &= $handle_1['response']['success'];
                }
                if ($request->hasFile('place_image2')) {
                    $handle_2 = $this->update_file($request, 'place_image2', 'pecs', $pec->id, $pec->place_image2);
                    $pec->update(['place_image2' => $handle_2['response']['payload']]);
                    $save &= $handle_2['response']['success'];
                }
            }

            // Guardamos en DataModel
            $this->control_data($pec, 'update');

            // actualiza el beneficiario que viene con el pec
            // actualiza y elimina un beneficiario, en caso que el usuario decida quitar un beneficiario ya creado
            // actualiza los beneficiarios ya creados e inserta un beneficiario en caso que el usuario decida agregar uno nuevo

            if ($save) {
                BeneficiaryPec::query()->where('pec_id', '=', $pec->id)->delete();

                $array = explode(",", $request->beneficiary_list);
                if (count($array) > 0) {
                    for ($i = 0; $i < count($array); $i++) {

                        BeneficiaryPec::create([
                            'pec_id' => $pec->id,
                            'beneficiary_id' => $array[$i],
                        ]);
                    }
                }
            }
            DB::commit();
            return response()->json(['items' => 'Datos actualizados correctamente', 'success' => true]);
        } catch (\Exception $ex) {
            report($ex);
            DB::rollBack();
            return response()->json(['error' => 'Algo salio mal ' . $ex->getMessage() . 'Codigo ' . $ex->getCode(), 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    //método para borrar un registro
    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    //método para validar campos
    public function getValidate($data, $method)
    {
        $validate = [
            'consecutive' => 'required',
            'nac_id' => 'required',
            'neighborhood_id' => 'required',
            'place' => 'required|string|max:190',
            'activity_date' => 'required',
            'place_address' => 'required|string|max:3500',
            'start_time' => 'required',
            'final_hour' => 'required|after:start_time',
            'place_description' => 'required|max:3500',
            'place_type' => 'required',
            'place_image1' => $method != 'update' ? 'required|mimes:application/pdf,pdf,png,webp,jpg,jpeg|max:' . (500 * 1049000) : 'bail',
            'place_image2' => $method != 'update' ? 'required|mimes:application/pdf,pdf,png,webp,jpg,jpeg|max:' . (500 * 1049000) : 'bail',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
        ];

        $attrs = [
            'consecutive' => 'Consecutivo',
            'nac_id' => 'Nac',
            'neighborhood_id' => 'Barrio',
            'place' => 'Lugar',
            'activity_date' => 'Fecha de la actividad',
            'place_address' => 'Dirección del lugar',
            'start_time' => 'Hora inicio',
            'final_hour' => 'Hora final',
            'place_description' => 'Descripción del lugar',
            'place_type' => 'Tipo lugar',
            'place_image1' => 'Imagen del lugar 1',
            'place_image2' => 'Imagen del lugar 2',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }


    public function getByRangeActivityDate($filter, $initDate, $lastDate)
    {
        // $carbono = Carbon::parse(now());
        $rol_id = $this->getIdRolUserAuth();
        if ($rol_id == config('roles.root') || $rol_id == config('roles.super_root') || $rol_id == config('roles.gestor') || $rol_id == config('roles.apoyo_al_seguimiento_monitoreo') || $rol_id == config('roles.lider_instructor') || $rol_id == config('roles.lider_embajador')) {
            // Temporalmente se pone Arreglar esto porque no quiere cargar al Editar Ensamble
            return $this->model
                ->select(['id', 'consecutive', 'activity_date', 'start_time', 'final_hour'])
                // ->whereBetween('activity_date', [$initDate, $lastDate])
                // ->where('consecutive', 'like', '%'. $filter . '%')
                // ->where('created_by', '=', Auth::id())
                ->orderBy('activity_date')
                ->get();
        }

        if ($rol_id == config('roles.instructor') || $rol_id == config('roles.monitor')) {
            return $this->model
                ->select(['id', 'consecutive', 'activity_date', 'start_time', 'final_hour'])
                // ->whereBetween('activity_date', [$initDate, $lastDate])
                // ->where('consecutive', 'like', '%'. $filter . '%')
                ->where('created_by', '=', Auth::id())
                ->orderBy('activity_date')
                ->get();
        }

        //return DB::table('pecs')->where('activity_date', '>=', $firtDay)->where('activity_date', '<=', $lastDay)->get();
    }
}