<?php


namespace App\Models\GoogleSheet;


use Illuminate\Support\Collection;

class Normalizer
{
    static public function cleanUp($rawValues):Collection
    {
        $collection = collect($rawValues);

        // remove first header row
        $headerRow = $collection->shift();

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

        return $collection;
    }
}
