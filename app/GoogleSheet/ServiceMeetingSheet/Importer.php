<?php


namespace App\GoogleSheet\ServiceMeetingSheet;

class Importer
{
    private $rawValues;


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

            return true;
        });
    }
}
