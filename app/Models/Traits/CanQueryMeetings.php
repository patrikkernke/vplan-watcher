<?php


namespace App\Models\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait CanQueryMeetings
{
    public function scopeAfter($query, Carbon $date = null): Builder
    {
        if (is_null($date)) $date = now();

        return $query->whereDate(
            'start_at',
            '>=',
            $date->toDatetimestring()
        );
    }
}
