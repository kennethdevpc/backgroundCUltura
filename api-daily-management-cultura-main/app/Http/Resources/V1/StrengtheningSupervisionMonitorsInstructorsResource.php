<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class StrengtheningSupervisionMonitorsInstructorsResource extends JsonResource
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
            'status' => $this->status,
            'reject_message' => $this->reject_message,
            'revision_date' => $this->revision_date,
            'nac' => $this->nac ?? '',
            'role' => $this->role ?? '',
            'user' => $this->user ?? '',
            'platform_registration_date' => $this->platform_registration_date,
            'address' => $this->address,
            'pec_reached_target' => $this->pec_reached_target,
            'pedagogicals_reached_target' => $this->pedagogicals_reached_target,
            'attendance_list' => $this->attendance_list,
            'validated_pec_time' => $this->validated_pec_time,
            'description' => $this->description,
            'comments' => $this->comments,
            'development_activity_image' => $this->development_activity_image,
            'evidence_participation_image' => $this->evidence_participation_image,
            'created_by' => $this->createdBy,
            'created_at' => $this->created_at,
            'super_coordinator_id' => $this->consecutive,
        ];
    }
}
