<?php


namespace App\Models\GoogleSheet\GuestTalksSheet\Mapper;


use App\Models\Congress;
use App\Models\GoogleSheet\GuestTalksSheet\Column;
use Illuminate\Support\Carbon;

class CongressMapper implements Mapper
{
    public static function map($row)
    {
        $congress = Congress::create([
            'startAt' => Carbon::createFromFormat('d.m.y H:i', $row[Column::DATE]),
            'motto_id' => $row[Column::DISPOSITION],
            'motto' => $row[Column::TOPIC],
            'part' => $row[Column::CONGREGATION]
        ]);
    }
}
