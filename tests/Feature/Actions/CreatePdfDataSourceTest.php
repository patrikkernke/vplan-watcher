<?php

namespace Tests\Feature\Actions;

use App\Actions\CreatePdfDataSource;
use Database\Seeders\PdfDataServiceMeetingsSeeder;
use Database\Seeders\PdfDataWeekendMeetingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreatePdfDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_valid_json_file_as_pdf_data_source_for_meetings()
    {
        // Arrange
        Storage::fake('pdf-sources');
        $storage = Storage::disk('pdf-sources');
        $this->seed(PdfDataWeekendMeetingsSeeder::class);

        // Act
        CreatePdfDataSource::forWeekendMeetings();

        // Assert
        $storage->assertExists('weekend-meetings.json');
        $json = $storage->get('weekend-meetings.json');
        $this->assertJson($json);
        $this->assertIsArray(json_decode($json));
    }

    /** @test */
    public function it_creates_a_valid_json_file_as_pdf_data_source_for_service_meetings()
    {
        // Arrange
        Storage::fake('pdf-sources');
        $storage = Storage::disk('pdf-sources');
        $this->seed(PdfDataServiceMeetingsSeeder::class);

        // Act
        CreatePdfDataSource::forServiceMeetings();

        // Assert
        $storage->assertExists('service-meetings.json');
        $json = $storage->get('service-meetings.json');
        $this->assertJson($json);
        $this->assertIsObject(json_decode($json));
    }
}
