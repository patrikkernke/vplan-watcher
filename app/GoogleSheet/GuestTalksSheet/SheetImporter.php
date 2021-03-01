<?php


namespace App\GoogleSheet\GuestTalksSheet;

class SheetImporter
{
    const SPREADSHEET_ID = '1eqwBV8UuYmWQLmpZrgElr0QL2iiBQetjrHDgikSg1CY';
    const SHEETNAME = 'Gastvorträge';
    const RANGE = 'C:K';

    private $rawValues;

    private $mapper = [
        'Vortrag' => \App\GoogleSheet\GuestTalksSheet\Mapper\PublicTalkMapper::class,
        'Kongress' => \App\GoogleSheet\GuestTalksSheet\Mapper\CongressMapper::class,
        'GM' => \App\GoogleSheet\GuestTalksSheet\Mapper\MemorialMeetingMapper::class,
        'Sondervortrag' => \App\GoogleSheet\GuestTalksSheet\Mapper\SpecialTalkMapper::class,
        'Dienstwoche' => \App\GoogleSheet\GuestTalksSheet\Mapper\CircuitOverseerPublicTalkMapper::class,
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
