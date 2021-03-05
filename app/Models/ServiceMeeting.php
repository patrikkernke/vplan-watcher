<?php

namespace App\Models;

use App\Models\Traits\CanQueryMeetings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ServiceMeeting extends Model
{
    use HasFactory, CanQueryMeetings;

    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime: Y-m-d H:i'
    ];

    /**
     * --------------
     * Relationships
     * --------------
     */
    public function fieldServiceGroup(): BelongsTo
    {
        return $this->belongsTo(FieldServiceGroup::class);
    }

    /**
     * ---------
     * Methods
     * ---------
     */

    public function forCongregation(): ServiceMeeting
    {
        $this->type = 'congregation';
        return $this;
    }

    public function isForCongregation(): bool
    {
        return $this->type === 'congregation';
    }

    public function forFieldServiceGroup(FieldServiceGroup $group)
    {
        $this->type = 'field_service_group';
        $this->field_service_group_id = $group->id;
        return $this;
    }

    public function isForFieldServiceGroup(): bool
    {
        return $this->type === 'field_service_group';
    }

    public function forServiceWeek(): ServiceMeeting
    {
        $this->type = 'service_week';
        return $this;
    }

    public function isForServiceWeek(): bool
    {
        return $this->type === 'service_week';
    }

    public function visitServiceOverseer(): ServiceMeeting
    {
        $this->is_visit_service_overseer = true;
        return $this;
    }

    public function isVisitServiceOverseer(): bool
    {
        return $this->is_visit_service_overseer;
    }

    /**
     * --------------
     * Query scopes
     * --------------
     */

    public function scopeOnlyForCongregation($query): Builder
    {
        return $query->where('type', 'congregation');
    }

    public function scopeOnlyForServiceWeek($query): Builder
    {
        return $query->where('type', 'service_week');
    }

    public function scopeOnlyForFieldServiceGroup($query): Builder
    {
        return $query->where('type', 'field_service_group');
    }

    /**
     * --------------
     * Exporter
     * --------------
     */
    public function exportForPdfSource():array
    {
        $baseExport = collect([
            'type' => $this->type,
            'date' => $this->start_at->translatedFormat('d. M'),
            'time' => $this->start_at->translatedFormat('H:i'),
            'leader' => $this->leader,
            'zoom' => config('zoom.congregation.service_meeting')
        ]);

        if ($this->isForFieldServiceGroup()) {
            $groupKey = Str::of($this->fieldServiceGroup->name)->lower()->slug('_');
            $baseExport = $baseExport->merge([
                'is_visit_service_overseer' => (bool) $this->is_visit_service_overseer,
                'field_service_group' => $this->fieldServiceGroup->exportForPdfSource(),
                'zoom' => config('zoom.field_service_group.' . $groupKey)
            ]);
        }

        return $baseExport->toArray();
    }
}
