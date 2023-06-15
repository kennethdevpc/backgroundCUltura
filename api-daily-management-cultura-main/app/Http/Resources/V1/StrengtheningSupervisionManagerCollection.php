<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StrengtheningSupervisionManagerCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_strengtheningSupervisionManager'),
            "success" => true,
            "action" => "Consulta Visita Supervisión Gestores",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnologia',
                'authors'=>'Jorge Usuga'
            ],
            'type'=>'strengtheningSupervisionMans'
        ];
    }
}
