<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Meeting */
class MeetingResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'start_at' => $this->start_at->toDateTimeString(),
            'type' => $this->type,
            'chairman' => $this->chairman,
            'schedule' => $this->schedule()->map(function ($scheduleItem) {
                return $scheduleItem->exportForApi();
            })
        ];
    }
}
