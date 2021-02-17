<?php


namespace App\Models\GoogleSheet\GuestTalksSheet\Mapper;


use App\Models\GoogleSheet\GuestTalksSheet\ColumnMap;
use App\Models\PublicTalk;
use App\Models\WatchtowerStudy;
use App\Models\WeekendMeeting;
use Illuminate\Support\Carbon;

class CircuitOverseerTalk implements Mapper
{
    static public function map($row)
    {
        $meeting = new WeekendMeeting();
        $meeting->startAt = Carbon::createFromFormat('d.m.y H:i', $row[ColumnMap::DATE])->toDateTimeString();
        $meeting->chairman = $row[ColumnMap::CHAIRMAN];
        $meeting->save();

        $publicTalk = new PublicTalk();
        $publicTalk->startAt = $meeting->startAt;
        $publicTalk->speaker = $row[ColumnMap::SPEAKER];
        $publicTalk->congregation = $row[ColumnMap::CONGREGATION];
        $publicTalk->disposition = $row[ColumnMap::DISPOSITION];
        $publicTalk->topic = $row[ColumnMap::TOPIC];
        $meeting->publicTalks()->save($publicTalk)->toArray();

        $watchtowerStudy = new WatchtowerStudy();
        $watchtowerStudy->startAt = $meeting->startAt->copy()->addMinutes(35);
        $meeting->watchtowerStudy()->save($watchtowerStudy);
    }
}
