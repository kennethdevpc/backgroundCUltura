<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
			'id'=>$this->id,
            'name'=>$this->name,
			'beneficiaries'=>$this->beneficiaries ?? '',
            'created_at'=>$this->created_at,
            'user'=>$this->user->name ?? '',
            'role'=>$this->user->profile->role->name ?? ''
		];
    }
}
