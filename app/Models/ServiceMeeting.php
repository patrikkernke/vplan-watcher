<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceMeeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'startAt' => 'datetime: Y-m-d H:i'
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

    public function visitCircuitOverseer(): ServiceMeeting
    {
        $this->is_visit_circuit_overseer = true;
        return $this;
    }

    public function isVisitCircuitOverseer(): bool
    {
        return $this->is_visit_circuit_overseer;
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
}
