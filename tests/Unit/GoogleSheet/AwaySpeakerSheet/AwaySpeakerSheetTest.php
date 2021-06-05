<?php

namespace Tests\Unit\GoogleSheet\ServiceMeetingsSheet;

use App\GoogleSheet\Normalizer;
use App\GoogleSheet\AwaySpeakerSheet\SheetImporter;
use App\Models\AwaySpeaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class AwaySpeakerSheetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection
     */
    private Collection $dummyResponse;

    /** @test */
    public function it_has_connection_data_for_the_belonging_google_sheet()
    {
        // Assert
        $this->assertNotEmpty(SheetImporter::SPREADSHEET_ID);
        $this->assertNotEmpty(SheetImporter::SHEETNAME);
        $this->assertNotEmpty(SheetImporter::RANGE);
    }

    /** @test */
    public function it_imports_away_speakers()
    {
        // Arrange
        $normalizedResponse = $this->getNormalizedDummyResponse();
        // Act
        (new SheetImporter($normalizedResponse))->import();
        // Assert
        $this->assertCount($normalizedResponse->count(), AwaySpeaker::all());
    }

    /**
     * Helper to get a dummy response from the file
     *
     * @return Collection
     */
    public function getNormalizedDummyResponse():Collection
    {
        if (empty($this->dummyResponse)) {
            $this->dummyResponse = Normalizer::cleanUp(
                require base_path('tests/Dummies/googlesheets-response-redner-neuwied.php')
            );
        }

        return $this->dummyResponse;
    }
}
