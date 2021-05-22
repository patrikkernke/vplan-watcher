<?php

namespace App\Models\Schedule\Item;

use App\Models\Schedule\ScheduleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ReflectionClass;

class CircuitOverseerTalk extends ScheduleItem
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime:Y-m-d H:i'
    ];

    public function exportForPdfSource():array
    {
        return [
            'type' => (new ReflectionClass($this))->getShortName(),
            'circuitOverseer' => $this->circuitOverseer,
            'topic' => $this->topic,
        ];
    }

    public function exportForApi():array
    {
        return [
            'type' => (new ReflectionClass($this))->getShortName(),
            'circuitOverseer' => $this->circuitOverseer,
            'topic' => $this->topic,
        ];
    }
}
