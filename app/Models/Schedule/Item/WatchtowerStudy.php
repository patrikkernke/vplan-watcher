<?php

namespace App\Models\Schedule\Item;

use App\Models\Schedule\ScheduleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WatchtowerStudy extends ScheduleItem
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'startAt' => 'datetime:Y-m-d H:s'
    ];
}
