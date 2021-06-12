<?php


namespace App\GoogleSheet\AwaySpeakerSheet;

use App\Models\AwaySpeaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SheetImporter
{
    const SPREADSHEET_ID = '1eqwBV8UuYmWQLmpZrgElr0QL2iiBQetjrHDgikSg1CY';
    const SHEETNAME = 'Redner Neuwied';
    const RANGE = 'A:H';

    private $rawValues;

    public function __construct($rawValues)
    {
        $this->rawValues = $rawValues;
    }

    public function import()
    {
        $this->rawValues->each(function($row) {
            AwaySpeaker::create([
                'firstname' => $row[Column::FIRSTNAME],
                'lastname' => $row[Column::LASTNAME],
                'dispositions' => Str::of($row[Column::DISPOSITIONS])->split('/[\s,]+/'),
                'email' => $row[Column::EMAIL],
                'phone' => $row[Column::PHONE],
                'may_give_speak_away' => Str::of($row[Column::MAY_GIVE_SPEAK_AWAY])->lower()->is('true'),
                'is_dag' => Str::of($row[Column::IS_DAG])->lower()->is('true'),
                'notes' => $row[Column::NOTES],
            ]);


            return true;
        });
    }

    public function cleanUpDatabase(): SheetImporter
    {
        DB::table('away_speakers')->truncate();

        return $this;
    }
}
