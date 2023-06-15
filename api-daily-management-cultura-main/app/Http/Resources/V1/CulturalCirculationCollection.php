<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\CulturalCirculation;

class CulturalCirculationCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_culturalCirculation'),
            "success" => true,
            "action" => "Consulta Circulacion Cultural",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnologia',
                'authors'=>'Edgar Rojas'
            ],
            'type'=>'Cultural_Circulation'
        ];
    }
}
