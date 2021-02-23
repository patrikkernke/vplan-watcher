<?php

namespace App\Models\Schedule\Item;

use App\Models\Schedule\ScheduleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PublicTalk extends ScheduleItem
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'startAt' => 'datetime:Y-m-d H:i'
    ];
}
