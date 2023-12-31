<?php

namespace App\Http\Resources\V1;

use App\Models\ManagerMonitoring;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ManagerMonitoringCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_manager_monitorings'),
            "success" => true,
            "action" => "Consulta seguimiento gestor cultural",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Seguimiento Gestor Cultural',
                'authors'=>'Jean'
            ],
            'type'=>'manager_monitorings'
           ];
    }
}
