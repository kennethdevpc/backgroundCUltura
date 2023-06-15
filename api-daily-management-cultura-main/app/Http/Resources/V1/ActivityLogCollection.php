<?php

namespace App\Http\Resources\V1;

use App\Traits\UserDataTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ActivityLogCollection extends ResourceCollection
{
    use UserDataTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "count_page" =>$request->session()->get('count_page_activityLogs'),
            "success" => true,
            "action" => "Consulta Actividad de la plataforma",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'OpenCode SAS',
                'authors'=>'Jorge Usuga'
            ],
            'type'=>'ActivityLogs'
        ];
    }
}
