<?php

namespace Tests\Unit;

use App\Models\FieldServiceGroup;
use App\Models\ServiceMeeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ServiceMeetingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_retrieves_start_at_as_carbon()
    {
        // Arrange
        $meeting = ServiceMeeting::create([
            'start_at' => now()->toDateTimeString(),
            'type' => 'congregation'
        ]);
        // Act
        $start_at = $meeting->start_at;
        // Assert
        $this->assertInstanceOf(Carbon::class, $start_at);
    }

    /** @test */
    public function it_sets_type_for_congregation()
    {
        // Arrange
        $meeting = ServiceMeeting::factory()->make(['type' => null]);
        // Act
        $meeting->forCongregation()->save();
        // Assert
        $this->assertTrue($meeting->isForCongregation());
    }

    /** @test */
    public function it_sets_type_for_field_service_group()
    {
        // Arrange
        $meeting = ServiceMeeting::factory()->make(['type' => null]);
        $group = FieldServiceGroup::factory()->create();
        // Act
        $meeting->forFieldServiceGroup($group)->save();
        // Assert
        $this->assertTrue($meeting->isForFieldServiceGroup());
        $this->assertEquals($group->id, $meeting->fieldServiceGroup->id);
    }

    /** @test */
    public function it_sets_type_for_service_week()
    {
        // Arrange
        $meeting = ServiceMeeting::factory()->make(['type' => null]);
        // Act
        $meeting->forServiceWeek()->save();
        // Assert
        $this->assertTrue($meeting->isForServiceWeek());
    }

    /** @test */
    public function it_sets_visited_by_service_overseer()
    {
        // Arrange
        $meeting = ServiceMeeting::factory()->make();
        // Act
        $meeting->visitServiceOverseer()->save();
        // Assert
        $this->assertTrue($meeting->isVisitServiceOverseer());
    }

    /** @test */
    public function it_can_ask_only_for_congregation_service_meetings()
    {
        // Arrange
        ServiceMeeting::factory()->count(10)->forCongregation()->create();
        ServiceMeeting::factory()->count(4)->forFieldServiceGroup()->create();
        ServiceMeeting::factory()->count(3)->forFieldServiceGroup()->create();
        ServiceMeeting::factory()->count(1)->forServiceWeek()->create();
        // Act
        $meetings = ServiceMeeting::onlyForCongregation()->get();
        // Assert
        $meetings->each(function ($meeting) {
            $this->assertTrue($meeting->isForCongregation());
        });
    }

    /** @test */
    public function it_can_ask_only_for_service_week_service_meetings()
    {
        // Arrange
        ServiceMeeting::factory()->count(10)->forCongregation()->create();
        ServiceMeeting::factory()->count(4)->forFieldServiceGroup()->create();
        ServiceMeeting::factory()->count(3)->forFieldServiceGroup()->create();
        ServiceMeeting::factory()->count(1)->forServiceWeek()->create();
        // Act
        $meetings = ServiceMeeting::onlyForServiceWeek()->get();
        // Assert
        $meetings->each(function ($meeting) {
            $this->assertTrue($meeting->isForServiceWeek());
        });
    }

    /** @test */
    public function it_can_ask_only_for_field_service_group_meetings_in_general()
    {
        // Arrange
        ServiceMeeting::factory()->count(10)->forCongregation()->create();
        ServiceMeeting::factory()->count(4)->forFieldServiceGroup()->create();
        ServiceMeeting::factory()->count(3)->forFieldServiceGroup()->create();
        ServiceMeeting::factory()->count(1)->forServiceWeek()->create();
        // Act
        $meetings = ServiceMeeting::onlyForFieldServiceGroup()->get();
        // Assert
        $meetings->each(function ($meeting) {
            $this->assertTrue($meeting->isForFieldServiceGroup());
        });
    }

    /** @test */
    public function it_can_query_starting_from_given_date()
    {
        // Arrange
        $first = ServiceMeeting::factory()->create(['start_at' => now()]);
        $second = ServiceMeeting::factory()->create(['start_at' => now()->subWeeks(5)->toDateTimeString()]);
        $third = ServiceMeeting::factory()->create(['start_at' => now()->addWeeks(5)->toDateTimeString()]);
        // Act
        $meetings = ServiceMeeting::after(now()->subWeeks(4))->get();
        // Assert
        $this->assertCount(2, $meetings);
    }

    /** @test */
    public function it_exports_service_meetings_for_congregation_pdf_generation()
    {
        // Arrange
        $meeting = ServiceMeeting::factory()->forCongregation()->create();
        // Act
        $data = $meeting->exportForPdfSource();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals($meeting->start_at->translatedFormat('d. M'), $data['date']);
        $this->assertEquals($meeting->start_at->translatedFormat('H:i'), $data['time']);
        $this->assertEquals($meeting->type, $data['type']);
        $this->assertEquals($meeting->leader, $data['leader']);
    }

    /** @test */
    public function it_exports_service_meetings_for_field_service_group_pdf_generation()
    {
        // Arrange
        $group = FieldServiceGroup::factory()->create();
        $meeting = ServiceMeeting::factory()->make();
        $meeting->forFieldServiceGroup($group)->save();
        // Act
        $data = $meeting->exportForPdfSource();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals($meeting->type, $data['type']);
        $this->assertArrayHasKey('is_visit_service_overseer', $data);
        $this->assertIsBool($data['is_visit_service_overseer']);
        $this->assertArrayHasKey('field_service_group', $data);
    }
}
