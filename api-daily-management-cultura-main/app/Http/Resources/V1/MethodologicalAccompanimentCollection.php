<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MethodologicalAccompanimentCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_methodologicalAccompaniment'),
            "success" => true,
            "action" => "Consulta Acompañamiento metolodógico",
            'items' => $this->collection,
            'meta' => [
                'organization' => 'Acompañamiento metolodógico',
                'authors' => 'Jefri Alexander'
            ],
            'type' => 'methodological_accompaniments'
        ];
    }
}
