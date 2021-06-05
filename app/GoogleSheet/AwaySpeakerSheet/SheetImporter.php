<?php


namespace App\GoogleSheet\AwaySpeakerSheet;

use App\Models\AwaySpeaker;
use App\Models\FieldServiceGroup;
use App\Models\ServiceMeeting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

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
                'dispositions' => $row[Column::DISPOSITIONS],
                'email' => $row[Column::EMAIL],
                'phone' => $row[Column::PHONE],
                'may_give_speak_away' => $row[Column::MAY_GIVE_SPEAK_AWAY],
                'is_dag' => $row[Column::IS_DAG],
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
