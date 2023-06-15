<?php

namespace App\Http\Resources\V1;

use App\Models\MethodologicalMonitoring;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MethodologicalInstructionCollection extends ResourceCollection
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
            "count_page" => $request->session()->get('count_page_methodological_instructions'),
            "success" => true,
            "action" => "Consulta de instrucciones Metodologicas",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnología',
                'authors'=>'Jefri'
            ],
            'type'=>'Methodologica instruction'
           ];
    }
}
