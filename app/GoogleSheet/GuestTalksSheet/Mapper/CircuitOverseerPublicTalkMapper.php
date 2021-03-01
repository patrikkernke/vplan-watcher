<?php


namespace App\GoogleSheet\GuestTalksSheet\Mapper;


use App\GoogleSheet\GuestTalksSheet\Column;
use App\Models\Meeting;
use App\Models\Schedule\Item\CircuitOverseerTalk;
use App\Models\Schedule\Item\WatchtowerStudy;
use Illuminate\Support\Carbon;

class CircuitOverseerPublicTalkMapper implements MapperInterface
{
    static public function map($row)
    {
        $meeting = Meeting::create([
            'type'     => 'Dienstwoche',
            'startAt'  => Carbon::createFromFormat('d.m.y H:i', $row[Column::DATE])->toDateTimeString(),
            'chairman' => $row[Column::CHAIRMAN]
        ]);

        $meeting->addToSchedule(CircuitOverseerTalk::create([
            'startAt'         => $meeting->startAt,
            'circuitOverseer' => $row[Column::SPEAKER],
            'topic'           => $row[Column::TOPIC]
        ]));

        $meeting->addToSchedule(WatchtowerStudy::create([
            'startAt' => $meeting->startAt->copy()->addMinutes(35)
        ]));
    }
}
