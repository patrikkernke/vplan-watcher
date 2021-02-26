<?php

namespace Tests\Feature\Actions;

use App\Actions\CreatePdfDataSource;
use Database\Seeders\PdfDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreatePdfDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_valid_json_file_as_pdf_data_source()
    {
        // Arrange
        Storage::fake('pdf-sources');
        $storage = Storage::disk('pdf-sources');
        $this->seed(PdfDataSeeder::class);

        // Act
        CreatePdfDataSource::weekendMeetings();

        // Assert
        $storage->assertExists('weekend-meetings.json');
        $json = $storage->get('weekend-meetings.json');
        $this->assertJson($json);
        $this->assertIsArray(json_decode($json));
    }
}
