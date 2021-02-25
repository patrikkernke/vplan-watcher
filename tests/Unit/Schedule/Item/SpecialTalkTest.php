<?php

namespace Tests\Unit\Schedule\Item;

use App\Models\Schedule\Item\SpecialTalk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecialTalkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_exports_data_for_pdf_generation()
    {
        // Arrange
        $talk = SpecialTalk::factory()->create();
        // Act
        $data = $talk->exportForPdfSource();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals('SpecialTalk', $data['type']);
        $this->assertEquals($talk->speaker, $data['speaker']);
        $this->assertEquals($talk->topic, $data['topic']);
        $this->assertEquals($talk->congregation, $data['congregation']);
    }
}
