<?php

namespace Tests\Unit\Schedule\Item;

use App\Models\Schedule\Item\CircuitOverseerTalk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CircuitOverseerTalkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_exports_data_for_pdf_generation()
    {
        // Arrange
        $talk = CircuitOverseerTalk::factory()->create();
        // Act
        $data = $talk->exportForPdfSource();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals('CircuitOverseerTalk', $data['type']);
        $this->assertEquals($talk->speacircuitOverseerker, $data['circuitOverseer']);
        $this->assertEquals($talk->topic, $data['topic']);
    }
}
