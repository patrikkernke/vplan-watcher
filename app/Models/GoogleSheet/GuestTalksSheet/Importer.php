<?php


namespace App\Models\GoogleSheet\GuestTalksSheet;

class Importer
{
    private $rawValues;

    private $mapper = [
        'Vortrag' => \App\Models\GoogleSheet\GuestTalksSheet\Mapper\PublicTalkMapper::class,
        'Kongress' => \App\Models\GoogleSheet\GuestTalksSheet\Mapper\CongressMapper::class,
        'GM' => \App\Models\GoogleSheet\GuestTalksSheet\Mapper\MemorialMeetingMapper::class,
        'Sondervortrag' => \App\Models\GoogleSheet\GuestTalksSheet\Mapper\SpecialTalkMapper::class,
        'Dienstwoche' => \App\Models\GoogleSheet\GuestTalksSheet\Mapper\CircuitOverseerPublicTalkMapper::class,
    ];

    public function __construct($rawValues)
    {
        $this->rawValues = collect($rawValues);
    }

    public function import()
    {
        $this->rawValues->each(function($row) {

            if (empty($row[Column::DATE])) {
                true; // continue loop
            }

            $rowType = $row[Column::TYPE];

            if (! array_key_exists($rowType, $this->mapper)) {
                return true; // continue loop
            }

            if (! class_exists($this->mapper[$rowType])) {
                return true; // continue loop
            }

            $mapperClass = $this->mapper[$rowType];
            $mapperClass::map($row);

            return true;
        });
    }
}
