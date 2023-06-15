<?php

namespace App\Http\Resources\V1;

use App\Models\MethodologicalSheetsOne;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MethodologicalSheetsOneCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_methodologicalSheetsOne'),
            "success" => true,
            "action" => "Consulta de ficha de planeación",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnología',
                'authors'=>'Pezedev'
            ],
            'type'=>'Notification'
           ];
    }
}
