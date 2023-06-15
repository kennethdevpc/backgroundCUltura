<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class MethodologicalSheetsOneResource extends JsonResource
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
            'datasheet' => $this->datasheet,
            'status' => $this->status,
            'semillero_name' => $this->semillero_name,
            'date_ini' => $this->date_ini,
            'date_fin' => $this->date_fin,
            'filter_level' => $this->filter_level,
            'worked_expertise' => $this->worked_expertise,
            'characteristics_process' => $this->characteristics_process,
            'objective_process' => $this->objective_process,
            'comments' => $this->comments,
            'group' => $this->group ?? '',
            'cultural_right_id' => $this->cultural_right_id,
            'orientation_id' => $this->orientation_id,
            'value' => $this->value,
            'lineament_id' => $this->lineament_id,
            'created_by' => $this->createdBy,
            'created_at' => $this->created_at,
        ];
    }
}
