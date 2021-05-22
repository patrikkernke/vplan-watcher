<?php

namespace Tests\Unit;

use App\Models\Meeting;
use App\Models\Schedule\Item\PublicTalk;
use App\Models\Schedule\Item\WatchtowerStudy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShortcutsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_does_not_grant_access_for_guests()
    {
        // Arrange
        // Act
        $guestResponse = $this->getJson('/api/shortcuts/meetings/next/weekend-meeting');
        // Assert
        $guestResponse->assertStatus(401);
    }

    /** @test */
    public function it_allows_access_only_for_users_through_easy_token_authentication()
    {
        // Arrange
        $this->actingAs($user = User::factory()->create());
        $token = Str::random(40);
        $user->tokens()->create([
            'name' => 'Test Token',
            'token' => hash('sha256', $token),
            'abilities' => ['read'],
        ]);

        // Act
        $response = $this->getJson("/api/shortcuts/meetings/next/weekend-meeting?token=$token");
        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_weekend_meeting_for_current_week()
    {
        // Arrange
        Sanctum::actingAs(
            User::factory()->create(), ['read'], 'easy'
        );

        Meeting::factory()->weekendMeeting()->atWeekFromNow(2)->create();
        Meeting::factory()->weekendMeeting()->atWeekFromNow(1)->create();
        $currentWeekendMeeting = Meeting::factory()->weekendMeeting()->atWeekFromNow(0)->create();
        $currentWeekendMeeting->addToSchedule(PublicTalk::factory()->create());
        $currentWeekendMeeting->addToSchedule(WatchtowerStudy::factory()->create());
        Meeting::factory()->weekendMeeting()->atWeekFromNow(1)->create();
        Meeting::factory()->weekendMeeting()->atWeekFromNow(2)->create();
        // Act
        $response = $this->get('/api/shortcuts/meetings/next/weekend-meeting');
        // Assert
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.type', $currentWeekendMeeting->type)
            ->assertJsonPath('data.start_at', $currentWeekendMeeting->start_at->toDateTimeString())
            ->assertJsonPath('data.chairman', $currentWeekendMeeting->chairman);
        $this->assertArrayHasKey('schedule', $response['data']);

    }
}
