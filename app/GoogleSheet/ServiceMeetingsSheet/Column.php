<?php


namespace App\GoogleSheet\ServiceMeetingsSheet;

/**
 * Class Column
 *
 * Maps array key to semantic column name defined in the google table
 * https://docs.google.com/spreadsheets/d/1eqwBV8UuYmWQLmpZrgElr0QL2iiBQetjrHDgikSg1CY/edit#gid=832353376
 *
 */
class Column
{
    const DATE = 0;
    const WEEKDAY = 1;
    const TIME_1 = 2;
    const LEADER_1 = 3;
    const TIME_2 = 4;
    const LEADER_2 = 5;
    // 6 is an empty column (divider)
    const IRLICH = 7;
    const BENDORF_1 = 8;
    const NIEDERBIEBER = 9;
    const NEUWIED_1 = 10;
    const BENDORF_2 = 11;
    const TUERKISCH = 12;
    const NEUWIED_2 = 13;
}
