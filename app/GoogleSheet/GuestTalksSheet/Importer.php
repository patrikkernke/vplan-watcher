<?php


namespace App\GoogleSheet\GuestTalksSheet;

class Importer
{
    private $rawValues;

    private $mapper = [
        'Vortrag' => \App\GoogleSheet\ServiceMeetingSheet\Mapper\PublicTalkMapper::class,
        'Kongress' => \App\GoogleSheet\ServiceMeetingSheet\Mapper\CongressMapper::class,
        'GM' => \App\GoogleSheet\ServiceMeetingSheet\Mapper\MemorialMeetingMapper::class,
        'Sondervortrag' => \App\GoogleSheet\ServiceMeetingSheet\Mapper\SpecialTalkMapper::class,
        'Dienstwoche' => \App\GoogleSheet\ServiceMeetingSheet\Mapper\CircuitOverseerPublicTalkMapper::class,
    ];

    public function __construct($rawValues)
    {
        $this->rawValues = collect($rawValues);
    }

    public function import()
    {
        $this->rawValues->each(function($row) {

            if (empty($row[Column::DATE])) {
                return true; // continue loop
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
