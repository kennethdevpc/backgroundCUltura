<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class NeighborhoodResource extends JsonResource
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
			'nac'=>$this->nac ?? '',
			'nac_id'=>$this->nac_id ?? '',
            'author'=>$this->user ?? '',
			'created_at' => $this->created_at
		];
    }
}
