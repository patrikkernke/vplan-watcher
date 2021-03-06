<?php


namespace App\GoogleSheet\GuestTalksSheet\Mapper;

use App\GoogleSheet\GuestTalksSheet\Column;
use App\Models\Meeting;
use App\Models\Schedule\Item\Congress;
use Illuminate\Support\Carbon;

class CongressMapper implements MapperInterface
{
    public static function map($row)
    {
        $meeting = Meeting::create([
            'type'     => 'Kongress',
            'start_at'  => Carbon::createFromFormat('d.m.y H:i', $row[Column::DATE])->toDateTimeString(),
        ]);

        $meeting->addToSchedule(Congress::create([
            'start_at' => Carbon::createFromFormat('d.m.y H:i', $row[Column::DATE]),
            'motto_id' => $row[Column::DISPOSITION],
            'motto' => $row[Column::TOPIC],
            'part' => $row[Column::CONGREGATION]
        ]));
    }
}
