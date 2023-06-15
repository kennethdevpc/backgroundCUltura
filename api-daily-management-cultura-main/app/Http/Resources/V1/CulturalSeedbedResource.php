<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CulturalSeedbedResource extends JsonResource
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
            'id' => $this->id,
            'date' => $this->date,
            'filter_level' => $this->filter_level,
            'pec_id' => $this->pec_id,
            'datasheet_planning' => $this->datasheetPlanning,
            'description' => $this->description,
            'objective_process' => $this->objective_process,
            'cultural_right_id' => $this->cultural_right_id,
            'lineament_id' => $this->lineament_id,
            'orientation_id' => $this->orientation_id,
            'level_domain_description' => $this->level_domain_description,
            'values' => $this->values,
            'quantity_members' => $this->quantity_members,
            'artistic_expertise' => $this->artistic_expertise,
            'observations' => $this->observations,
            'development_activity_image' => $this->development_activity_image,
            'evidence_participation_image' => $this->evidence_participation_image,
            'created_by' => $this->user ?? '',
            'status' => $this->status,
            'consecutive' => $this->consecutive,
            'reject_message' => $this->reject_message,
            'created_at' => $this->created_at,
            'group_id' => $this->group_id,
            'beneficiaries' => $this->beneficiary ?? '',
            'datasheet'=>$this->datasheet
        ];
    }
}
