<?php


namespace App\Models\GoogleSheet\GuestTalksSheet\Mapper;


use App\Models\GoogleSheet\GuestTalksSheet\Column;

use App\Models\Meeting;
use App\Models\Schedule\Item\PublicTalk;
use App\Models\Schedule\Item\WatchtowerStudy;
use Illuminate\Support\Carbon;

class PublicTalkMapper implements Mapper
{
    static public function map($row)
    {
        $meeting = Meeting::create([
            'type'     => 'Öffentliche Zusammenkunft',
            'startAt'  => Carbon::createFromFormat('d.m.y H:i', $row[Column::DATE])->toDateTimeString(),
            'chairman' => $row[Column::CHAIRMAN],
        ]);

        $meeting->addToSchedule(PublicTalk::create([
            'startAt'      => $meeting->startAt,
            'speaker'      => $row[Column::SPEAKER],
            'congregation' => $row[Column::CONGREGATION],
            'disposition'  => $row[Column::DISPOSITION],
            'topic'        => $row[Column::TOPIC],
        ]));

        $meeting->addToSchedule(WatchtowerStudy::create([
            'startAt' => $meeting->startAt->copy()->addMinutes(35),
            'reader'  => $row[Column::READER]
        ]));
    }
}
