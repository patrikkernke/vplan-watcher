<?php


namespace App\GoogleSheet\GuestTalksSheet;

/**
 * Class Column
 *
 * Maps array key to semantic column name defined in the google table
 * https://docs.google.com/spreadsheets/d/1eqwBV8UuYmWQLmpZrgElr0QL2iiBQetjrHDgikSg1CY/edit#gid=0
 *
 * @package App\Models\GoogleSheet\GuestTalksSheet
 */
class Column
{
    const DATE = 0;
    const TYPE = 1;
    const SPEAKER = 2;
    const CONGREGATION = 3;
    const DISPOSITION = 4;
    const TOPIC = 5;
    const CHAIRMAN = 6;
    const READER = 7;
    const AWAY_SPEAKER = 8;
}
