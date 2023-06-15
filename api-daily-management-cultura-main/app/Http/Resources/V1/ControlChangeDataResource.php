<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ControlChangeDataResource extends JsonResource
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
            'user' => $this->user,
            'data_model_id'=>$this->data_model_id,
            'data_model_type' => $this->data_model_type,
            'action' => $this->action,
            'data_original' => $this->data_original,
            'data_change' => $this->data_change,
            'created_at_date' => $this->created_at,
        ];
    }
}
