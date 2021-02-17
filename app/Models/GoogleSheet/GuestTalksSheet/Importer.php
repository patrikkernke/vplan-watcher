<?php


namespace App\Models\GoogleSheet\GuestTalksSheet;

use App\Models\GoogleSheet\GuestTalksSheet\Mapper;
use Illuminate\Support\Str;

class Importer
{
    private $rawValues;

    private $mapper = [
        'Vortrag' => \App\Models\GoogleSheet\GuestTalksSheet\Mapper\PublicTalk::class,
        'Kongress' => \App\Models\GoogleSheet\GuestTalksSheet\Mapper\Congress::class,
        'GM' => \App\Models\GoogleSheet\GuestTalksSheet\Mapper\MemorialMeeting::class,
        'Sondervortrag' => \App\Models\GoogleSheet\GuestTalksSheet\Mapper\SpecialTalk::class,
        'Dienstwoche' => \App\Models\GoogleSheet\GuestTalksSheet\Mapper\CircuitOverseerTalk::class,
    ];

    public function __construct($rawValues)
    {
        $this->rawValues = collect($rawValues);
    }

    public function import()
    {
        $this->rawValues->each(function($row) {

            if (empty($row[ColumnMap::DATE])) {
                true; // continue loop
            }

            $rowType = $row[ColumnMap::TYPE];

            if (! array_key_exists($rowType, $this->mapper)) {
                return true; // continue loop
            }

            $map = $this->mapper[$rowType] . '::map';;
            $map($row);

            return true;
        });
    }
}
