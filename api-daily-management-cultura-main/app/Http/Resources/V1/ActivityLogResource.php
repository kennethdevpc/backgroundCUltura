<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
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
            'log_name' => $this->log_name,
            'description' => $this->description,
            'subject_type' => $this->subject_type,
            'subject' => $this->subject,
            'causer' => $this->causer,
            'event' => $this->event,
            'subject_id' => $this->subject_id,
            'causer_type' => $this->causer_type,
            'created_at' => $this->created_at?->format('d-m-Y'),
        ];
    }
}
