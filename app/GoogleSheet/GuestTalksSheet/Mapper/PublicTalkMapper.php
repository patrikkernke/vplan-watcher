<?php


namespace App\GoogleSheet\GuestTalksSheet\Mapper;


use App\GoogleSheet\GuestTalksSheet\Column;

use App\Models\Meeting;
use App\Models\Schedule\Item\PublicTalk;
use App\Models\Schedule\Item\WatchtowerStudy;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PublicTalkMapper implements MapperInterface
{
    static public function map($row)
    {
        $meeting = Meeting::create([
            'type'     => 'Öffentliche Zusammenkunft',
            'start_at'  => Carbon::createFromFormat('d.m.y H:i', $row[Column::DATE])->toDateTimeString(),
            'chairman' => $row[Column::CHAIRMAN],
        ]);

        $meeting->addToSchedule(PublicTalk::create([
            'start_at'      => $meeting->start_at,
            'speaker'      => $row[Column::SPEAKER],
            'congregation' => $row[Column::CONGREGATION],
            'disposition'  => $row[Column::DISPOSITION],
            'topic'        => self::removePlaceholders($row[Column::TOPIC]),
        ]));

        $meeting->addToSchedule(WatchtowerStudy::create([
            'start_at' => $meeting->start_at->copy()->addMinutes(35),
            'reader'  => $row[Column::READER]
        ]));

        return $meeting;
    }

    /**
     * @param $value
     * @return null
     */
    private static function removePlaceholders($value)
    {
        $normalizedValue = Str::lower($value);

        if ( Str::contains($normalizedValue, 'thema') )
            return null;
        if ( Str::contains($normalizedValue, 'motto') )
            return null;

        return  $value;
    }
}
