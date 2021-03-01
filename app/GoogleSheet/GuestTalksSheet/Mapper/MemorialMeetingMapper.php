<?php


namespace App\GoogleSheet\GuestTalksSheet\Mapper;


use App\GoogleSheet\GuestTalksSheet\Column;
use App\Models\Meeting;
use App\Models\MemorialMeeting;
use App\Models\Schedule\Item\PublicTalk;
use Illuminate\Support\Carbon;

class MemorialMeetingMapper implements MapperInterface
{
    static public function map($row)
    {
        $meeting = Meeting::create([
            'type'     => 'Gedächtnismahl',
            'start_at'  => Carbon::createFromFormat('d.m.y H:i', $row[Column::DATE])->toDateTimeString(),
            'chairman' => $row[Column::CHAIRMAN]
        ]);

        $meeting->addToSchedule(PublicTalk::create([
            'start_at'      => $meeting->start_at,
            'speaker'      => $row[Column::SPEAKER],
            'congregation' => $row[Column::CONGREGATION],
            'disposition'  => $row[Column::DISPOSITION],
            'topic'        => $row[Column::TOPIC]
        ]));
    }
}
