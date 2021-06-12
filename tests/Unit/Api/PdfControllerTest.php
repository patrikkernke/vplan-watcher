<?php

namespace Tests\Unit\Api;

use App\Models\AwaySpeaker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PdfControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_does_not_grant_access_for_guests()
    {
        // Arrange
        // Act
        $guestResponse = $this->getJson('/api/pdf/data/weekend-meetings');
        // Assert
        $guestResponse->assertUnauthorized();
    }

    /** @test */
    public function it_allows_access_only_for_users_with_a_bearer_token()
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create(), ['*']);
        // Act
        $response = $this->getJson(route('pdf.data.weekend-meetings'));
        // Assert
        $response->assertOk();
    }

    /** @test */
    public function it_delivers_data_for_away_speakers()
    {
        $this->withoutExceptionHandling();
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
}
