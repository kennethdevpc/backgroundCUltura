<?php

namespace App\Http\Resources\V1;

use App\Traits\FunctionGeneralTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class MethodologicalMonitoringResource extends JsonResource
{
    use  FunctionGeneralTrait;
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
            'aggregates' => $this->aggregates,
            'roles' => $this->roles ?? '',
            'audited' => $this->audited,
            'comments' => $this->comments,
            'consecutive' => $this->consecutive,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,

            'date_realization' => $this->date_realization,
            'date_planning_fin' => $this->date_planning_fin,
            'date_planning_ini' => $this->date_planning_ini,

            'development_activity_image' => $this->development_activity_image,
            'evidence_participation_image' => $this->evidence_participation_image,

            'cultural_right' =>$this->cultural_right ?? '',
            'nac' => $this->nac ?? '',
            'orientation' => $this->orientation ?? '',

            'cultural_right_id' =>$this->cultural_right_id,
            'nac_id' => $this->nac_id,
            'orientation_id' => $this->orientation_id,

            'value_id' => $this->value,
            'value' =>$this->data($this->value, 'values'),
            'lineament_id' => $this->lineament_id,
            'lineament' =>$this->data($this->lineament_id, 'lineaments'),
            'objective_process' => $this->objective_process,
            'reject_message' => $this->reject_message,
            'status' => $this->status,
            'strengthening_comments' => $this->strengthening_comments,
            'strengthening_type' => $this->strengthening_type,
            'strengthening_type_name' => $this->data($this->strengthening_type,'strengthening_types'),
            'topics_to_strengthened' => $this->topics_to_strengthened,
            'user' => $this->user ?? "",
            'datasheet' =>$this->datasheet
        ];
    }
}
