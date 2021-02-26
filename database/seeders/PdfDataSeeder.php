<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Schedule\Item\CircuitOverseerTalk;
use App\Models\Schedule\Item\PublicTalk;
use App\Models\Schedule\Item\SpecialTalk;
use App\Models\Schedule\Item\WatchtowerStudy;
use Illuminate\Database\Seeder;

class PdfDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createDefaultWeekendMeeting(-10);
        $this->createDefaultWeekendMeeting(-9);
        $this->createDefaultWeekendMeeting(-8);
        $this->createDefaultWeekendMeeting(-7);
        $this->createDefaultWeekendMeeting(-6);
        $this->createWeekendMeetingWithCircuitOverseerTalk(-5);
        $this->createDefaultWeekendMeeting(-4);
        $this->createDefaultWeekendMeeting(-3);
        $this->createDefaultWeekendMeeting(-2);
        $this->createDefaultWeekendMeeting(-1);
        $this->createDefaultWeekendMeeting(1);
        $this->createWeekendMeetingWithSpecialTalk(2);
        $this->createDefaultWeekendMeeting(3);
        $this->createDefaultWeekendMeeting(4);
        $this->createDefaultWeekendMeeting(5);
        $this->createDefaultWeekendMeeting(6);
        $this->createWeekendMeetingWithCircuitOverseerTalk(7);
        $this->createDefaultWeekendMeeting(8);
        $this->createDefaultWeekendMeeting(9);
        $this->createDefaultWeekendMeeting(10);
    }

    /**
     * Creates a default weekend meeting
     *
     * @param int $weeks
     *
     * @return \App\Models\Meeting
     */
    protected function createDefaultWeekendMeeting(int $weeks):Meeting
    {
        $meeting = Meeting::factory()
            ->weekendMeeting()
            ->atWeekFromNow($weeks)
            ->create();

        return $meeting
            ->addToSchedule(PublicTalk::factory()->atTime($meeting->startAt->copy())->create())
            ->addToSchedule(WatchtowerStudy::factory()->atTime($meeting->startAt->copy()->addMinutes(35))->create());
    }

    protected function createWeekendMeetingWithSpecialTalk(int $weeks):Meeting
    {
        $meeting = Meeting::factory()
            ->weekendMeeting()
            ->atWeekFromNow($weeks)
            ->create();

        return $meeting
            ->addToSchedule(SpecialTalk::factory()->atTime($meeting->startAt->copy())->create())
            ->addToSchedule(WatchtowerStudy::factory()->atTime($meeting->startAt->copy()->addMinutes(35))->create());
    }

    protected function createWeekendMeetingWithCircuitOverseerTalk(int $weeks):Meeting
    {
        $meeting = Meeting::factory()
            ->weekendMeeting()
            ->atWeekFromNow($weeks)
            ->create();

        return $meeting
            ->addToSchedule(CircuitOverseerTalk::factory()->atTime($meeting->startAt->copy())->create())
            ->addToSchedule(WatchtowerStudy::factory()->atTime($meeting->startAt->copy()->addMinutes(35))->create(['reader' => null]));
    }
}
