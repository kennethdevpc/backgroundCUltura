<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StrengtheningOfMonitoringCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_strengtheningOfMonitoring'),
            "success" => true,
            "action" => "Consulta fortalecimiento seguimiento",
            'items'=>$this->collection,
            'meta'=>[
                'organization' => [
                    'Arte y tecnologia',
                    'OpenCode SAS'
                ],
                'authors' => [
                    'Jefri Martínez',
                    'Jorge Usuga'
                ]
            ],
            'type'=>'strengthening_of_monitorings'
           ];
    }
}
