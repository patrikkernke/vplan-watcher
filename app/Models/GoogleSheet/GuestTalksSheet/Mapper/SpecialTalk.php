<?php


namespace App\Models\GoogleSheet\GuestTalksSheet\Mapper;


use App\Models\GoogleSheet\GuestTalksSheet\ColumnMap;
use App\Models\SpecialTalk as SpecialTalkModel;
use App\Models\WatchtowerStudy;
use App\Models\WeekendMeeting;
use Illuminate\Support\Carbon;

class SpecialTalk implements Mapper
{
    const TYPE_STRING = 'Sondervortrag';

    static public function map($row)
    {
        $meeting = new WeekendMeeting();
        $meeting->startAt = Carbon::createFromFormat('d.m.y H:i', $row[ColumnMap::DATE])->toDateTimeString();
        $meeting->chairman = $row[ColumnMap::CHAIRMAN];
        $meeting->save();

        $specialTalk = new SpecialTalkModel();
        $specialTalk->startAt = $meeting->startAt;
        $specialTalk->speaker = $row[ColumnMap::SPEAKER];
        $specialTalk->congregation = $row[ColumnMap::CONGREGATION];
        $specialTalk->disposition = $row[ColumnMap::DISPOSITION];
        $specialTalk->topic = $row[ColumnMap::TOPIC];
        $meeting->specialTalk()->save($specialTalk)->toArray();

        $watchtowerStudy = new WatchtowerStudy();
        $watchtowerStudy->startAt = $meeting->startAt->copy()->addMinutes(35);
        $watchtowerStudy->reader = $row[ColumnMap::READER];
        $meeting->watchtowerStudy()->save($watchtowerStudy);
    }
}
