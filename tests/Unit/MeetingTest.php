<?php

namespace Tests\Unit;

use App\Models\Meeting;
use App\Models\Schedule\Item\CircuitOverseerTalk;
use App\Models\Schedule\Item\PublicTalk;
use App\Models\Schedule\Item\SpecialTalk;
use App\Models\Schedule\Item\WatchtowerStudy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeetingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_meeting_with_minimal_info()
    {
        $meeting = Meeting::create([
            'startAt' => '2021-02-12 13:00:00',
            'type' => 'Öffentliche Zusammenkunft'
        ]);

        $this->assertDatabaseHas('meetings', [
            'startAt' => $meeting->startAt,
            'type' => $meeting->type
        ]);
    }

    /** @test */
    public function it_can_store_the_chairman_optionally()
    {
        $meeting = Meeting::create([

            'startAt' => '2021-02-12 13:00:00',
            'type' => 'Öffentliche Zusammenkunft'
        ]);

        $meeting->chairman = 'Patrik Kernke';
        $meeting->save();

        $this->assertDatabaseHas('meetings', [
            'startAt' => $meeting->startAt,
            'chairman' => $meeting->chairman
        ]);
    }

    /**
     * @test
     * @dataProvider scheduleItemsProvider
     */
    public function it_can_add_schedule_items($scheduleItemClass)
     {
         $meeting = Meeting::factory()->create();

         $scheduleItem1 = $meeting->addToSchedule($scheduleItemClass::factory()->create());
         $scheduleItem2 = $meeting->addToSchedule($scheduleItemClass::factory()->create());

         $schedule = $meeting->schedule();

         $this->assertCount(2, $schedule);
         $this->assertTrue($schedule->contains('id', $scheduleItem1->id));
         $this->assertTrue($schedule->contains('id', $scheduleItem2->id));
     }

    /** @test */
    public function it_can_mix_different_schedule_items()
    {
        $meeting = Meeting::factory()->create();

        $scheduleItem1 = $meeting->addToSchedule(PublicTalk::factory()->create());
        $scheduleItem2 = $meeting->addToSchedule(WatchtowerStudy::factory()->create());

        $schedule = $meeting->schedule();

        $this->assertCount(2, $schedule);
        $this->assertTrue($schedule->contains('id', $scheduleItem1->id));
        $this->assertTrue($schedule->contains('id', $scheduleItem2->id));
    }

    /** @test */
    public function it_sorts_schedule_items_by_datetime()
    {
        $meeting = Meeting::factory()->create();

        $scheduleItem1 = PublicTalk::factory()->create(['startAt' => '2021-02-12 11:30:00']);
        $scheduleItem2 = WatchtowerStudy::factory()->create(['startAt' => '2021-02-12 10:45:00']);
        $scheduleItem3 = PublicTalk::factory()->create(['startAt' => '2021-02-12 10:00:00']);

        $meeting->addToSchedule($scheduleItem1)
            ->addToSchedule($scheduleItem2)
            ->addToSchedule($scheduleItem3);

        $schedule = $meeting->schedule();

        $this->assertCount(3, $schedule);
        $this->assertEquals($scheduleItem3->id, $schedule->first()->id);
        $this->assertEquals($scheduleItem1->id, $schedule->last()->id);
    }

    /** @test */
    public function it_exports_data_for_pdf_generation()
    {
        // Arrange
        $meeting = Meeting::factory()->create();
        // Act
        $data = $meeting->exportForPdfSource();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals($meeting->startAt->translatedFormat('d. M'), $data['date']);
        $this->assertEquals($meeting->chairman, $data['chairman']);
        $this->assertArrayHasKey('schedule', $data);
    }


    /**
     * Providers
     */

    public function scheduleItemsProvider():array
    {
        return [
            'PublicTalk' => [PublicTalk::class],
            'WatchtowerStudy' => [WatchtowerStudy::class],
            'SpecialTalk' => [SpecialTalk::class],
            'CircuitOverseerTalk' => [CircuitOverseerTalk::class],
        ];
    }
}
