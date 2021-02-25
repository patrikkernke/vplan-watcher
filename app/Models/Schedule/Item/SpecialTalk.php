<?php

namespace App\Models\Schedule\Item;

use App\Models\Schedule\ScheduleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ReflectionClass;

class SpecialTalk extends ScheduleItem
{
    use HasFactory;

    protected $guarded = [];

    public function exportForPdfSource():array
    {
        return [
            'type' => (new ReflectionClass($this))->getShortName(),
            'speaker' => $this->speaker,
            'topic' => $this->topic,
            'congregation' => $this->congregation,
        ];
    }
}
