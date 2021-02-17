<?php


namespace App\Models\GoogleSheet\GuestTalksSheet\Mapper;


use App\Models\GoogleSheet\GuestTalksSheet\ColumnMap;
use App\Models\MemorialMeeting as MemorialMeetingModel;
use Illuminate\Support\Carbon;

class MemorialMeeting implements Mapper
{
    const TYPE_STRING = 'GM';

    static public function map($row)
    {
        $meeting = new MemorialMeetingModel();
        $meeting->startAt = Carbon::createFromFormat('d.m.y H:i', $row[ColumnMap::DATE])->toDateTimeString();
        $meeting->chairman = $row[ColumnMap::CHAIRMAN];
        $meeting->speaker = $row[ColumnMap::SPEAKER];
        $meeting->disposition = $row[ColumnMap::DISPOSITION];
        $meeting->topic = $row[ColumnMap::TOPIC];
        $meeting->save();
    }
}
