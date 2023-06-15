<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class BinnacleShowCulturalResource extends JsonResource
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
            'date_range' => $this->date_range,
            'activity' => $this->activity,
            'expertise' => $this->expertise,
            'artistic_participation' => $this->artistic_participation,
            'reached_target' => $this->reached_target,
            'sustein' => $this->sustein,
            'development_activity_image' => $this->development_activity_image,
            'evidence_participation_image' => $this->evidence_participation_image,
            'aforo_pdf' => $this->aforo_pdf,
            'number_attendees' => $this->number_attendees,
            'created_by' => $this->created_user ?? ''
        ];
    }
}
