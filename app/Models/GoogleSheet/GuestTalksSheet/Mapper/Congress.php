<?php


namespace App\Models\GoogleSheet\GuestTalksSheet\Mapper;


use App\Models\Congress as CongressModel;
use App\Models\GoogleSheet\GuestTalksSheet\ColumnMap;
use Illuminate\Support\Carbon;

class Congress implements Mapper
{
    const TYPE_STRING = 'Kongress';

    public static function map($row)
    {
        $congress = new CongressModel();
        $congress->startAt = Carbon::createFromFormat('d.m.y H:i', $row[ColumnMap::DATE])->toDateTimeString();
        $congress->motto_id = $row[ColumnMap::DISPOSITION];
        $congress->motto = $row[ColumnMap::TOPIC];
        $congress->part = $row[ColumnMap::CONGREGATION];
        $congress->save();
    }
}
