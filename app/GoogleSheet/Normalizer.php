<?php


namespace App\GoogleSheet;


use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Normalizer
{
    static public function cleanUp($rawValues):Collection
    {
        $collection = collect($rawValues);

        // remove first header row
        $headerRow = $collection->shift();

        // remove completely empty rows without any information
        $collection = $collection->filter(function ($row) {
            return Str::of( collect($row)->join('') )
                ->lower()
                ->replace('false', '')
                ->isNotEmpty();
        });


        // convert "" -> null
        $collection = $collection->map(function ($row) {
            return collect($row)->map(function ($value) {
                return empty($value) ? null : $value;
            })->toArray();
        });

        // fill up missing columns
        $collection = $collection->map(function ($row) use ($headerRow) {
            while (count($row) < count($headerRow)) {
                array_push($row, null);
            }

            return $row;
         });

        // trim strings
        $collection = $collection->map(function ($row) {
            return collect($row)->map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            })->toArray();
        });

        return $collection;
    }
}
