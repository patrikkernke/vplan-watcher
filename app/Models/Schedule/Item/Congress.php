<?php

namespace App\Models\Schedule\Item;

use App\Models\Schedule\ScheduleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ReflectionClass;
use function Symfony\Component\Translation\t;

class Congress extends ScheduleItem
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
            'date' => $this->start_at->translatedFormat('d. M'),
            'motto_id' => $this->motto_id,
            'motto' => $this->motto,
            'part' => $this->part,
        ];
    }

    public function exportForApi():array
    {
        return [
            'type' => (new ReflectionClass($this))->getShortName(),
            'date' => $this->start_at->translatedFormat('d. M'),
            'motto_id' => $this->motto_id,
            'motto' => $this->motto,
            'part' => $this->part,
        ];
    }
}
