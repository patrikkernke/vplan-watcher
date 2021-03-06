<?php

namespace App\Models\Schedule\Item;

use App\Models\Schedule\ScheduleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ReflectionClass;

class WatchtowerStudy extends ScheduleItem
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime:Y-m-d H:s'
    ];

    public function exportForPdfSource():array
    {
        return [
            'type' => (new ReflectionClass($this))->getShortName(),
            'conductor' => $this->conductor,
            'reader' => $this->reader,
        ];
    }

    public function exportForApi():array
    {
        return [
            'type' => (new ReflectionClass($this))->getShortName(),
            'conductor' => $this->conductor,
            'reader' => $this->reader,
        ];
    }
}
