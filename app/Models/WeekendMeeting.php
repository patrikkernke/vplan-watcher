<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class WeekendMeeting extends Model
{
    use HasFactory;

    protected $casts = [
        'startAt' => 'datetime:Y-m-d H:i'
    ];

    public function program():Collection
    {
        $program = collect();

        $relationTypes = [
            'specialTalk', 'publicTalks', 'watchtowerStudy'
        ];

        foreach ($relationTypes as $relation) {
            $this->{$relation}()->each(function($item) use ($program) {
                $program->add($item);
            });
        }

        return $program->sortBy('startAt');
    }

    public function publicTalks():HasMany
    {
        return $this->hasMany(PublicTalk::class, 'meeting_id');
    }

    public function specialTalk():HasOne
    {
        return $this->hasOne(SpecialTalk::class, 'meeting_id');
    }

    public function watchtowerStudy():HasOne
    {
        return $this->hasOne(WatchtowerStudy::class, 'meeting_id');
    }
}
