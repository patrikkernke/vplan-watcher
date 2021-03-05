<?php


namespace App\GoogleSheet\ServiceMeetingsSheet;

use App\Models\FieldServiceGroup;
use App\Models\ServiceMeeting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class SheetImporter
{
    const SPREADSHEET_ID = '1eqwBV8UuYmWQLmpZrgElr0QL2iiBQetjrHDgikSg1CY';
    const SHEETNAME = 'Treffpunkte';
    const RANGE = 'B:O';

    private $rawValues;

    public function __construct($rawValues)
    {
        $this->rawValues = $rawValues;
    }

    public function import()
    {
        $this->rawValues->each(function($row) {
            $date = Str::of($row[Column::DATE]);

            if ($date->isEmpty()) {
                return true; // continue loop
            }

            // Congregation Meetings
            $time1 = Str::of($row[Column::TIME_1]);
            if ($time1->isNotEmpty()) {
                $this->addCongregationServiceMeeting($date, $time1, $row[Column::LEADER_1]);
            }

            $time2 = Str::of($row[Column::TIME_2]);
            if ($time2->isNotEmpty()) {
                $this->addCongregationServiceMeeting($date, $time2, $row[Column::LEADER_2]);
            }

            // Field Service Groups
            $groups = collect([
                Column::IRLICH => 'Irlich',
                Column::BENDORF_1 => 'Bendorf 1',
                Column::NIEDERBIEBER => 'Niederbieber',
                Column::NEUWIED_1 => 'Neuwied 1',
                Column::BENDORF_2  => 'Bendorf 2',
                Column::TUERKISCH  => 'Türkisch',
                Column::NEUWIED_2  => 'Neuwied 2',
            ]);

            $groups->each(function($groupName, $columnKey) use ($date, $row) {
                $group = FieldServiceGroup::firstOrCreate(['name' => $groupName]);

                $meetingTime = Str::of($row[$columnKey]);
                if ($meetingTime->isNotEmpty()) {
                    $this->addFieldGroupServiceMeeting($group, $date, $meetingTime);
                }
            });

            return true;
        });
    }

    public function cleanUpDatabase(): SheetImporter
    {
        DB::table('service_meetings')->truncate();
        DB::table('field_service_groups')->truncate();

        return $this;
    }

    protected function addCongregationServiceMeeting(Stringable $date, Stringable $time, $leader)
    {
        $meeting = new ServiceMeeting();
        $meeting->forCongregation();

        if ($time->contains('DW')) {
            $time = $time->replace('DW', '')->trim();
            $meeting->forServiceWeek();
        }

        $datetime = Carbon::createFromFormat('d.m.y', $date);
        if ($time->isEmpty()) $datetime->setTimeFromTimeString('00:00');
        if ($time->isNotEmpty()) $datetime->setTimeFromTimeString($time);


        $meeting->start_at = $datetime;
        $meeting->leader = $leader;

        $meeting->save();
    }

    protected function addFieldGroupServiceMeeting(FieldServiceGroup $group, Stringable $date, Stringable $time)
    {
        $meeting = new ServiceMeeting();
        $meeting->forFieldServiceGroup($group);

        $time = $time->replace('*', '');

        if ($time->contains('DA')) {
            $time = $time->replace('DA', '')->trim();
            $meeting->visitServiceOverseer();
        }

        $datetime = Carbon::createFromFormat('d.m.y', $date);
        if ($time->isNotEmpty()) $datetime->setTimeFromTimeString($time);

        $meeting->start_at = $datetime;

        $meeting->save();
    }
}
