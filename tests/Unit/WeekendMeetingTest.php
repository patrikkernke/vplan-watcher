<?php

namespace Tests\Unit;

use App\Models\PublicTalk;
use App\Models\WatchtowerStudy;
use App\Models\WeekendMeeting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeekendMeetingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_gets_the_program_sorted_by_time()
    {
        $meeting = WeekendMeeting::factory()->create();

        $publicTalk1 = PublicTalk::factory()->create([
            'meeting_id' => $meeting->id,
            'startAt' => $meeting->startAt
        ]);
        $publicTalk2 = PublicTalk::factory()->create([
            'meeting_id' => $meeting->id,
            'startAt' => $meeting->startAt->copy()->addHours(2)
        ]);
        $watchtowerStudy = WatchtowerStudy::factory()->create([
            'meeting_id' => $meeting->id,
            'startAt' => $meeting->startAt->copy()->addHours(1)
        ]);

        $program = $meeting->program();

        $this->assertCount(3, $program);
        $this->assertEquals($publicTalk1->toArray(), $program->first()->toArray());
        $this->assertEquals($publicTalk2->toArray(), $program->last()->toArray());
    }
}
