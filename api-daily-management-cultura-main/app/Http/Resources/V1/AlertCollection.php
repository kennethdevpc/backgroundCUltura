<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AlertCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "count_page" =>$request->session()->get('count_page_alerts'),
            "success" => true,
            "action" => "Consulta Alertas de la plataforma",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'OpenCode SAS',
                'authors'=>'Jorge Usuga'
            ],
            'type'=>'Alerts',
        ];
    }
}
