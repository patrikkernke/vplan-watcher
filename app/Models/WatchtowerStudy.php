<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchtowerStudy extends Model
{
    use HasFactory;

    protected $casts = [
        'startAt' => 'datetime:Y-m-d H:s'
    ];

    public function meeting():BelongsTo
    {
        return $this->belongsTo(WeekendMeeting::class, 'meeting_id');
    }
}
