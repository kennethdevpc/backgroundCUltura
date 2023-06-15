<?php

namespace App\Http\Resources\V1;

use App\Models\Binnacle;
use App\Traits\UserDataTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BinnacleCollection extends ResourceCollection
{
    use UserDataTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "count_page" => $request->session()->get('count_page_binnacles'),
            "success" => true,
            "action" => "Consulta bitacora",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnologia',
                'authors'=>'Jefri Martínez'
            ],
            'type'=>'binnacles'
           ];
    }
}
