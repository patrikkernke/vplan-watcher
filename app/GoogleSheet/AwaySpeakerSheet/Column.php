<?php


namespace App\GoogleSheet\AwaySpeakerSheet;

/**
 * Class Column
 *
 * Maps array key to semantic column name defined in the google table
 * https://docs.google.com/spreadsheets/d/1eqwBV8UuYmWQLmpZrgElr0QL2iiBQetjrHDgikSg1CY/edit#gid=832353376
 *
 */
class Column
{
    const FIRSTNAME = 0;
    const LASTNAME = 1;
    const DISPOSITIONS = 2;
    const EMAIL = 3;
    const PHONE = 4;
    const MAY_GIVE_SPEAK_AWAY = 5;
    const IS_DAG = 6;
    const NOTES = 7;
}
