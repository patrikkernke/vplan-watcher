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
            'startAt' => now()->toDateTimeString(),
            'type' => 'congregation'
        ]);
        // Act
        $startAt = $meeting->startAt;
        // Assert
        $this->assertInstanceOf(Carbon::class, $startAt);
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
        $meeting->visitCircuitOverseer()->save();
        // Assert
        $this->assertTrue($meeting->isVisitCircuitOverseer());
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
}
