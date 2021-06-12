<?php


namespace App\GoogleSheet\DispositionSheet;

use App\Models\AwaySpeaker;
use App\Models\Disposition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SheetImporter
{
    const SPREADSHEET_ID = '1eqwBV8UuYmWQLmpZrgElr0QL2iiBQetjrHDgikSg1CY';
    const SHEETNAME = 'Vortragsthemen';
    const RANGE = 'C:D';

    private $rawValues;

    public function __construct($rawValues)
    {
        $this->rawValues = $rawValues;
    }

    public function import()
    {
        $this->rawValues->each(function($row) {

            Disposition::create([
                'topic_id' => $row[Column::ID],
                'topic' => $row[Column::TOPIC],
            ]);

            return true;
        });
    }

    public function cleanUpDatabase(): SheetImporter
    {
        DB::table('dispositions')->truncate();

        return $this;
    }
}
