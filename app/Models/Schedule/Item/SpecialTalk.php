<?php

namespace App\Models\Schedule\Item;

use App\Models\Schedule\ScheduleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ReflectionClass;

class SpecialTalk extends ScheduleItem
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
            'speaker' => $this->speaker,
            'topic' => $this->topic,
            'congregation' => $this->congregation,
        ];
    }
    public function exportForApi():array
    {
        return [
            'type' => (new ReflectionClass($this))->getShortName(),
            'speaker' => $this->speaker,
            'disposition' => $this->disposition,
            'topic' => $this->topic,
            'congregation' => $this->congregation,
        ];
    }
}
