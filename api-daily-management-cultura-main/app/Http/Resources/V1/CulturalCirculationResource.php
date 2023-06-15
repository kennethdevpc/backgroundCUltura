<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CulturalCirculationResource extends JsonResource
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
            'status'=> $this->status,
            'consecutive'=> $this->consecutive,
            'keyactors_circulation_alliance'=> $this->keyactors_circulation_alliance,
            'pec_id'=> $this->pec_id,
            'datasheet_planning'=> $this->datasheet_planning,
            'event_name'=> $this->event_name,
            'filter_level'=> $this->filter_level,
            'description'=> $this->description,
            'nac_id'=> $this->nac_id,
            'other_nac'=> $this->other_nac,
           'quantity_members'=> $this->quantity_members,
            'public_characteristics'=> $this->public_characteristics,
            'cultural_right_id'=> $this->cultural_right_id,
            'lineament_id'=> $this->lineament_id,
            'orientation_id'=> $this->orientation_id,
            'values'=> $this->values,
            'artistic_expertise'=> $this->artistic_expertise,
            'participation_observations'=> $this->participation_observations,
            'development_activity_image'=> $this->development_activity_image,
            'evidence_participation_image'=> $this->evidence_participation_image,
            'aforo_pdf'=> $this->aforo_pdf,
            'number_attendees'=> $this->number_attendees,
            'reject_message'=> $this->reject_message,
            'created_by'=> $this->user ?? '',
            'created_at'=> $this->created_at,
            'datasheet'=>$this->datasheet
        ];
    }
}
