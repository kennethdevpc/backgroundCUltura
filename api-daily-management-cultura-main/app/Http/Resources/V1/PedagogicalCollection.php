<?php

namespace App\Http\Resources\V1;

use App\Models\Pedagogical;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PedagogicalCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_pedagogicals'),
            "success" => true,
            "action" => "Consulta ficha pedagogica",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Ficha Pedagogica',
                'authors'=>'Luis'
            ],
            'type'=>'entity_names'
           ];
    }
}
