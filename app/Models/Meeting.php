<?php

namespace App\Models;

use App\Models\Schedule\Item\CircuitOverseerTalk;
use App\Models\Schedule\Item\Congress;
use App\Models\Schedule\Item\PublicTalk;
use App\Models\Schedule\Item\SpecialTalk;
use App\Models\Schedule\Item\WatchtowerStudy;
use App\Models\Schedule\ScheduleItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use ReflectionClass;

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

        return $schedule->sortBy('startAt')->values();
    }

    /** todo@pk in trait extrahieren */
    public function scopeAllAfter($query, Carbon $date = null): Builder
    {
        if (is_null($date)) $date = now();

        return $query->whereDate(
            'startAt',
            '>=',
            $date->toDatetimestring()
        );
    }

    public function exportForPdfSource():array
    {
        return [
            'type' => $this->type,
            'date' => $this->startAt->translatedFormat('d. M'),
            'chairman' => $this->chairman,
            'schedule' => $this->schedule()->map(function ($scheduleItem) {
                return $scheduleItem->exportForPdfSource();
            })
        ];
    }
}
