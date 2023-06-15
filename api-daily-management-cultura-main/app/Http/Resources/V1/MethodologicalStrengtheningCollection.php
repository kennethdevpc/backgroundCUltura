<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MethodologicalStrengtheningCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_methodologicalStrengthening'),
            "success" => true,
            "action" => "Consulta Fortalecimiento metodológico",
            'items' => $this->collection,
            'meta' => [
                'organization' => 'Fortalecimiento metodológico',
                'authors' => 'Jefri Alexander'
            ],
            'type' => 'methodological_strengthenings'
        ];
    }
}
