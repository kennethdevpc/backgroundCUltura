<?php

namespace App\Http\Resources\V1;

use App\Traits\FunctionGeneralTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class MethodologicalStrengtheningResource extends JsonResource
{
    use FunctionGeneralTrait;
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
            'status'=>$this->status,
            'date'=>$this->date,
            'nac_id'=>$this->nac_id,
            'cultural_right_id' => $this->cultural_right_id,
            'cultural_right' => $this->cultural_right ?? '',
            'lineament_id' => $this->lineament_id,
            'orientation_id' => $this->orientation_id,
            'orientation' => $this->orientation ?? '',
            'value' =>$this->value,
            'value_name' => $this->data($this->value, 'values'),
            'lineament' =>$this->data($this->lineament_id, 'lineaments'),
            'comments' => $this->comments,
            'assistants'=>$this->assistants ?? '',
            'created_by'=>$this->created_by,
            'user'=>$this->user ?? '',
			'created_at'=>$this->created_at,
            'consecutive'=>$this->consecutive,
            'nac'=>$this->nac ?? '',
            'evidence_participation_image'=>$this->evidence_participation_image,
            'development_activity_image' =>$this->development_activity_image
		];
    }
}
