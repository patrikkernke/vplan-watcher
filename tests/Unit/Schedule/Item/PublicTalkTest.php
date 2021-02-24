<?php

namespace Tests\Unit\Schedule\Item;

use App\Models\Schedule\Item\PublicTalk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublicTalkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_exports_data_for_pdf_generation()
    {
        // Arrange
        $talk = PublicTalk::factory()->create();
        // Act
        $data = $talk->exportForPdfSource();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals('PublicTalk', $data['type']);
        $this->assertEquals($talk->speaker, $data['speaker']);
        $this->assertEquals($talk->topic, $data['topic']);
        $this->assertEquals($talk->congregation, $data['congregation']);
    }
}
