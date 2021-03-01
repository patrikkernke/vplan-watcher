<?php

namespace App\Models;

use App\Models\Schedule\Item\CircuitOverseerTalk;
use App\Models\Schedule\Item\Congress;
use App\Models\Schedule\Item\PublicTalk;
use App\Models\Schedule\Item\SpecialTalk;
use App\Models\Schedule\Item\WatchtowerStudy;
use App\Models\Schedule\ScheduleItem;
use App\Models\Traits\CanQueryMeetings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Meeting extends Model
{
    use HasFactory, CanQueryMeetings;

    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime:Y-m-d H:i'
    ];

    protected $scheduleItemClasses = [
        PublicTalk::class,
        WatchtowerStudy::class,
        SpecialTalk::class,
        CircuitOverseerTalk::class,
        Congress::class,
    ];

    public function addToSchedule(ScheduleItem $scheduleItem)
    {
        $this->hasMany(get_class($scheduleItem))->save($scheduleItem);

        return $this;
    }

    public function schedule():Collection
    {
        $schedule = collect();

        foreach ($this->scheduleItemClasses as $itemClass) {
            $items = $this->hasMany($itemClass);

            $items->each(function ($item) use ($schedule) {
                $schedule->add($item);
            });
        }

        return $schedule->sortBy('start_at')->values();
    }

    public function exportForPdfSource():array
    {
        return [
            'type' => $this->type,
            'date' => $this->start_at->translatedFormat('d. M'),
            'chairman' => $this->chairman,
            'schedule' => $this->schedule()->map(function ($scheduleItem) {
                return $scheduleItem->exportForPdfSource();
            })
        ];
    }
}
