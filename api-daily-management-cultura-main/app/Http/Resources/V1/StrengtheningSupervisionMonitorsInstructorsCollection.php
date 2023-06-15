<?php

namespace App\Http\Resources\V1;

use App\Models\StrengtheningSupervisionMonitorsInstructors;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StrengtheningSupervisionMonitorsInstructorsCollection extends ResourceCollection
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
            "count_page" => $request->session()->get('count_page_strengtheningSupervisionMonitors'),
            "success" => true,
            "action" => "Consulta fortalecimiento seguimiento",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnologia',
                'authors'=>'Jefri Martínez'
            ],
            'type'=>'strengthening_of_monitorings'
           ];
    }
}
