<?php

namespace App\Http\Resources\V1;

use App\Models\Neighborhood;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NeighborhoodCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_neighborhoods'),
            "success" => true,
            "action" => "Consulta barrios",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnologia',
                'authors'=>'Jefri Alexander'
            ],
            'type'=>'Neighborhood'
           ];
    }
}
