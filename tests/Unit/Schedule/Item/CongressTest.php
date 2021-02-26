<?php

namespace Tests\Unit\Schedule\Item;

use App\Models\Schedule\Item\Congress;
use App\Models\Schedule\ScheduleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CongressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_extends_schedule_item_class()
    {
        $this->assertInstanceOf(ScheduleItem::class, new Congress());
    }

    /** @test */
    public function it_exports_data_for_pdf_generation()
    {
        // Arrange
        $congress = Congress::factory()->create();
        // Act
        $data = $congress->exportForPdfSource();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals('Congress', $data['type']);
        $this->assertEquals($congress->startAt->translatedFormat('d. M'), $data['date']);
        $this->assertEquals($congress->motto_id, $data['motto_id']);
        $this->assertEquals($congress->motto, $data['motto']);
        $this->assertEquals($congress->part, $data['part']);
    }
}
