<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\CulturalSeedbed;

class CulturalSeedbedCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_culturalSeedBed'),
            "success" => true,
            "action" => "Consulta BITÁCORA SEMILLERO CULTURAL",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnologia',
                'authors'=>'Jean Pool Ramirez'
            ],
            'type'=>'cultural_seedbed'
        ];
    }
}
