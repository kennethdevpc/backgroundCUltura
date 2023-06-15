<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class MethodologicalSheetsTwoResource extends JsonResource
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
            'datasheet' => $this->datasheet,
            'consecutive' => $this->consecutive,
            'status' => $this->status,
            'activity_type' => $this->activity_type,
            'date_ini' => $this->date_ini,
            'date_fin' => $this->date_fin,
            'keyactors_participating_community' => $this->keyactors_participating_community,
            'objective_process' => $this->objective_process,
            'reached_target' => $this->reached_target,
            'sustein' => $this->sustein,
           // 'participants_number' => $this->participants_number,
            'development_activity_image' => $this->development_activity_image,
            'evidence_participation_image' => $this->evidence_participation_image,
            'aforo_pdf' => $this->aforo_pdf,
            'number_attendees' => $this->number_attendees,
            'created_by' => $this->createdBy,
            'created_at' => $this->created_at,
            'beneficiaries' => $this->beneficiary,
            'group_id' => $this->beneficiary[0]->group_id ?? '',
        ];
    }
}
