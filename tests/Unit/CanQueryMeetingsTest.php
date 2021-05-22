<?php

namespace Tests\Unit;

use App\Models\Meeting;
use App\Models\ServiceMeeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanQueryMeetingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_query_starting_from_given_date()
    {
        // Arrange
        $first = Meeting::factory()->create(['start_at' => now()]);
        $second = Meeting::factory()->create(['start_at' => now()->subWeeks(5)->toDateTimeString()]);
        $third = Meeting::factory()->create(['start_at' => now()->addWeeks(5)->toDateTimeString()]);
        // Act
        $meetings = Meeting::after(now()->subWeeks(4))->get();
        // Assert
        $this->assertCount(2, $meetings);
    }

    /**
     * @test
     * @dataProvider meetingClassesProvider
     */
    public function it_can_query_before_given_date($meetingClass)
    {
        // Arrange
        $refTime = now();
        $first = $meetingClass::factory()->create(['start_at' => $refTime]);
        $second = $meetingClass::factory()->create(['start_at' => $refTime->copy()->subWeeks(1)->toDateTimeString()]);
        $third = $meetingClass::factory()->create(['start_at' => $refTime->copy()->addWeeks(5)->toDateTimeString()]);
        // Act
        $meetings = $meetingClass::before($refTime)->get();
        // Assert
        $this->assertCount(1, $meetings);
    }

    /**
     * Provider
     * @return string[][]
     */
    public function meetingClassesProvider():array
    {
        return [
            'Meeting' => [Meeting::class],
            'ServiceMeeting' => [ServiceMeeting::class]
        ];
    }
}
