<?php

namespace Tests\Unit;

use App\Models\FieldServiceGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldServiceGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_exports_for_pdf_generation()
    {
        // Arrange
        $group = FieldServiceGroup::factory()->create();
        // Act
        $data = $group->exportForPdfSource();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals($group->name, $data['name']);
    }
}
