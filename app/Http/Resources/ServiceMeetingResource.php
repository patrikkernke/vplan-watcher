<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ServiceMeeting */
class ServiceMeetingResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start_at' => $this->start_at->toDateTimeString(),
            'type' => $this->type,
            'leader' => $this->leader,
            'is_visit_service_overseer' => $this->is_visit_service_overseer,
            'fieldServiceGroup' => $this->fieldServiceGroup,
            'field_service_group_id' => $this->field_service_group_id,
        ];
    }
}
