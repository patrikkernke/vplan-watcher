<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Schedule\Item\PublicTalk;
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
        $this->createDefaultWeekendMeeting(-3);
        $this->createDefaultWeekendMeeting(-2);
        $this->createDefaultWeekendMeeting(-1);
        $this->createDefaultWeekendMeeting(0);
        $this->createDefaultWeekendMeeting(1);
        $this->createDefaultWeekendMeeting(2);
        $this->createDefaultWeekendMeeting(3);
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
            ->addToSchedule(PublicTalk::factory()->atTime($meeting->startAt)->create())
            ->addToSchedule(WatchtowerStudy::factory()->atTime($meeting->startAt->copy()->addMinutes(35))->create());
    }
}
