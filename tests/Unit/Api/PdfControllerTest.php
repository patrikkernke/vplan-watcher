<?php

namespace Tests\Unit\Api;

use App\Models\AwaySpeaker;
use App\Models\ServiceMeeting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PdfControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider apiProviders
     */
    public function it_does_not_grant_access_for_guests($apiUrl)
    {
        // Arrange
        // Act
        $guestResponse = $this->getJson($apiUrl());
        // Assert
        $guestResponse->assertUnauthorized();
    }

    /**
     * @test
     * @dataProvider apiProviders
     */
    public function it_allows_access_only_for_users_with_a_bearer_token($apiUrl)
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create(), ['*']);
        // Act
        $response = $this->getJson($apiUrl());
        // Assert
        $response->assertOk();
    }

    /** @test */
    public function it_delivers_data_for_away_speakers()
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $speakers = AwaySpeaker::factory()->count(2)->create();
        // Act
        $response = $this->getJson(route('pdf.data.away-speaker'));
        // Assert
        $this->assertCount(2, $response['data']);
        $response->assertJsonStructure([ 'data' => [
            ['firstname', 'lastname', 'dispositions', 'email', 'phone', 'may_give_speak_away', 'is_dag']
        ]]);
    }

    /** @test */
    public function it_delivers_data_for_service_meetings_for_a_given_month()
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create(), ['*']);
        ServiceMeeting::factory()->thisMonth()->count(15)->create()->toArray();
        $now = now();
        // Act
        $response = $this->getJson(route('pdf.data.service-meetings', [
            'year' => $now->year,
            'month' => $now->month,
        ]));

        // Assert
        $response->assertJsonStructure([ 'data' => [
            '*' => [ // days
                '*' => [ // meetings
                    'start_at',
                    'type',
                    'is_visit_service_overseer',
                    'leader'
                ]
            ]
        ]]);
        $this->assertCount($now->daysInMonth, $response['data']);
        $this->assertCount(15, collect($response['data'])->flatten(1));
    }

    /**
     * Providers
     */

    public function apiProviders():array
    {
        return [
            'weekendMeetings' => [function() { return route('pdf.data.weekend-meetings');}],
            'awaySpeakers' => [function() { return route('pdf.data.away-speaker');}],
            'serviceMeetings' => [function() { return route('pdf.data.service-meetings');}],
        ];
    }
}
