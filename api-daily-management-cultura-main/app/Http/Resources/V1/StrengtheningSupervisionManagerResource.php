<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class StrengtheningSupervisionManagerResource extends JsonResource
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
            'consecutive' => $this->consecutive,
            'revision_date' => $this->revision_date,
            'address' => $this->address,
            'methodological_instruction_reached_target' => $this->methodological_instruction_reached_target,
            'frequency' => $this->frequency,
            'binnacle_registered_platform' => $this->binnacle_registered_platform,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'final_time' => $this->final_time,
            'comments' => $this->comments,
            'development_activity_image' => $this->development_activity_image,
            'evidence_participation_image' => $this->evidence_participation_image,
            'created_by' => $this->user->id ?? '',
            'user' => $this->user ?? '',
            'super_coordinator_id' => $this->superCoordinator ?? '',
            'status' => $this->status,
            'reject_message' => $this->reject_message,
            'nac_id' => $this->nac->id ?? '',
            'nac' => $this->nac ?? '',
            'user_manager' => $this->user_manager ?? '',
            'methodological_instruction' => $this->methodological_instruction ?? '',
            'methodological_instruction_id' => $this->methodological_instruction_id,
            'created_at' => $this->created_at,
            'user_associate_id'=>$this->user_associate_id,
            'binnacle_registered_plataform'=>$this->binnacle_registered_plataform
        ];
    }
}
