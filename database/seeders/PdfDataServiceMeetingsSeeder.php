<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Schedule\Item\CircuitOverseerTalk;
use App\Models\Schedule\Item\Congress;
use App\Models\Schedule\Item\PublicTalk;
use App\Models\Schedule\Item\SpecialTalk;
use App\Models\Schedule\Item\WatchtowerStudy;
use App\Models\ServiceMeeting;
use Illuminate\Database\Seeder;

class PdfDataServiceMeetingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($week = 0; $week <= 10; $week++) {
            ServiceMeeting::factory()
                ->forCongregation()
                ->create(['start_at' => now()->addWeeks($week)->setTime(10, 0, 0)]);
        }

        ServiceMeeting::factory()
            ->forServiceWeek()
            ->create(['start_at' => now()->addWeeks($week)->setTime(10, 0, 0)]);

        for ($week = 0; $week <= 10; $week++) {
            ServiceMeeting::factory()
                ->count(2)
                ->forFieldServiceGroup()
                ->create(['start_at' => now()->addWeeks($week)->setTime(12, 0, 0)]);
        }
    }
}
