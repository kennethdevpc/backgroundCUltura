<?php

namespace App\Http\Resources\V1;

use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
            "count_page" =>$request->session()->get('count_page_users'),
            "success" => true,
            "action" => "Consulta users",
            'items'=>$this->collection,
            'meta'=>[
                'organization'=>'users',
                'authors'=>'Jefri'
            ],
            'type'=>'users'
         ];
    }
}
