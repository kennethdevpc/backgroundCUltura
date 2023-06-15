<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CulturalEnsembleResource extends JsonResource
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
            'assembly_characteristics' => $this->assembly_characteristics,
            'objective_process' => $this->objective_process,
            'public_characteristics' => $this->public_characteristics,
            'cultural_right_id' => $this->cultural_right_id,
            'lineament_id' => $this->lineament_id,
            'orientation_id' => $this->orientation_id,
            'value' => $this->value,
            'artistic_expertise' => $this->artistic_expertise,
            'evaluate_aspects' => $this->evaluate_aspects,
            'evaluate_aspects_comments' => $this->evaluate_aspects_comments,
            'aforo_pdf' => $this->aforo_pdf,
            'development_activity_image' => $this->development_activity_image,
            'evidence_participation_image' => $this->evidence_participation_image,
            'number_attendees' => $this->number_attendees,
            'created_by' => $this->user ?? '',
            'created_at' => $this->created_at,
            'status' => $this->status,
            'consecutive' => $this->consecutive,
            'reject_message' => $this->reject_message,
            'datasheet'=>$this->datasheet

        ];
    }
}
