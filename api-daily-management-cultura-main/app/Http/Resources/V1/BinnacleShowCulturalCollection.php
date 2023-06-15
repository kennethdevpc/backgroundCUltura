<?php

namespace App\Http\Resources\V1;

use App\Models\BinnacleCulturalShow;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BinnacleShowCulturalCollection extends ResourceCollection
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
            "count_page" => $request->session()->get('count_page_binnacles_show_cultural'),
            "last_page" => BinnacleCulturalShow::latest()->paginate()->lastPage(),
            "success" => true,
            "action" => "Consulta ensamble cultural",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnologia',
                'authors'=>'Jean Pool Ramirez'
            ],
            'type'=>'Cultural_Circulation'
           ];
    }
}
