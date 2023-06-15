<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class MethodologicalAccompanimentResource extends JsonResource
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
            'assistants'=>$this->assistants ?? '',
            'date'=>$this->date,
            'others'=>$this->others,
            'objective_visit'=>$this->objective_visit,
            'aspects'=>explode (',', $this->aspects),
            'aspects_comments'=>$this->aspects_comments,
            'comments'=>$this->comments,
            'roles_associate'=>explode (',', $this->roles),
            'development_activity_image'=>$this->development_activity_image,
            'evidence_participation_image'=>$this->evidence_participation_image,
            'created_by'=>$this->created_by,
            'status'=>$this->status,
            'user'=>$this->user ?? '',
			'created_at'=>$this->created_at,
            'consecutive'=>$this->consecutive,
            'nac'=>$this->nacMetho ?? '',
            'nac_id'=>$this->nac_id
		];
    }
}
