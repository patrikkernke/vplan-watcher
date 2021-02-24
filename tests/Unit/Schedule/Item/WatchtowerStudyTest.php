<?php

namespace Tests\Unit\Schedule\Item;

use App\Models\Schedule\Item\WatchtowerStudy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WatchtowerStudyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_exports_data_for_pdf_generation()
    {
        // Arrange
        $watchtowerStudy = WatchtowerStudy::factory()->create();
        // Act
        $data = $watchtowerStudy->exportForPdfSource();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals('WatchtowerStudy', $data['type']);
        $this->assertEquals($watchtowerStudy->conductor, $data['conductor']);
        $this->assertEquals($watchtowerStudy->reader, $data['reader']);
    }
}
