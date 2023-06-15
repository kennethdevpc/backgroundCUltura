<?php

namespace App\Http\Resources\V1;

use App\Models\MethodologicalSheetsTwo;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MethodologicalSheetsTwoCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_methodologicalSheetsTwo'),
            "success" => true,
            "action" => "Consulta de ficha de planeación #2",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnología',
                'authors'=>'Pezedev'
            ],
            'type'=>'Notification'
           ];
    }
}
