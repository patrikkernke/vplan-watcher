<?php

namespace App\Models\Schedule;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class ScheduleItem extends Model
{
    public function meeting():BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Prepare data and export for pdf generation
     *
     * @return array
     */
    public function exportForPdfSource():array
    {
        return $this->toArray();
    }
}
