<?php

namespace App\Http\Resources\V1;

use App\Models\Inscriptions\Inscription;
use App\Traits\UserDataTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InscriptionsCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_inscriptions'),
            "success" => true,
            "action" => "Consulta inscripciones",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'Arte y tecnologia',
                'authors'=>'Jefri Alexander'
            ],
            'type'=>'dialogue_tables'
           ];
    }
}
