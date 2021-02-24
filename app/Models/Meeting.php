<?php

namespace App\Models;

use App\Models\Schedule\Item\CircuitOverseerTalk;
use App\Models\Schedule\Item\PublicTalk;
use App\Models\Schedule\Item\SpecialTalk;
use App\Models\Schedule\Item\WatchtowerStudy;
use App\Models\Schedule\ScheduleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Meeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'startAt' => 'datetime:Y-m-d H:i'
    ];

    protected $scheduleItemClasses = [
        PublicTalk::class,
        WatchtowerStudy::class,
        SpecialTalk::class,
        CircuitOverseerTalk::class,
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

        return $schedule->sortBy('startAt');
    }
}
