<?php

namespace Tests\Feature\Actions;

use App\Actions\CreatePdfDataSource;
use Database\Seeders\PdfDataServiceMeetingsSeeder;
use Database\Seeders\PdfDataWeekendMeetingsSeeder;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreatePdfDataSourceTest extends TestCase
{
    use RefreshDatabase;

    private Filesystem $storage;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('pdf-sources');
        $this->storage = Storage::disk('pdf-sources');
        $this->seed(PdfDataWeekendMeetingsSeeder::class);
        $this->seed(PdfDataServiceMeetingsSeeder::class);
    }

    /**
     * @test
     * @dataProvider sourceTypes
     * @param $sourceType
     */
    public function it_creates_and_stores_a_valid_json_file($sourceType)
    {
        // Arrange && Act
        $filename = CreatePdfDataSource::$sourceType();
        // Assert
        $this->storage->assertExists($filename);
        $json = $this->storage->get($filename);
        $this->assertJson($json);
    }

    /** @test */
    public function it_stores_weekend_meetings_as_array()
    {
        // Arrange && Act
        $filename = CreatePdfDataSource::forWeekendMeetings();
        // Assert
        $json = json_decode($this->storage->get($filename));
        $this->assertIsArray($json);
    }

    /** @test */
    public function it_stores_service_meetings_as_object()
    {
        // Arrange && Act
        $filename = CreatePdfDataSource::forServiceMeetings();
        // Assert
        $json = json_decode($this->storage->get($filename));
        $this->assertIsObject($json);
    }

    public function sourceTypes(): array
    {
        return [
            ['forServiceMeetings'],
            ['forWeekendMeetings']
        ];
    }
}
