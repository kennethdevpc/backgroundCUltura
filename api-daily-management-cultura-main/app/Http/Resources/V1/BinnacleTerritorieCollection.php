<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BinnacleTerritorieCollection extends ResourceCollection
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
            "count_page" => $request->session()->get('count_page_binnacleTerritorie'),
            "success" => true,
            "action" => "Consulta bitacora territorios",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnologia',
                'authors'=>'Jorge Usuga'
            ],
            'type'=>'BinnacleTerritories'
        ];
    }
}
