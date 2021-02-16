<?php

namespace Tests\Unit\GoogleSheet;

use App\Models\GoogleSheet\Normalizer;
use App\Models\GoogleSheet\GuestTalksSheetImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GuestTalksSheetImporterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array
     */
    private $dummyResponse = [];

    /** @test */
    public function it_recognizes_public_talks_and_creates_them()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[\App\Models\GoogleSheet\GuestTalksSheetImporter::TYPE_COLUMN] === "Vortrag";
        })->count();

        (new GuestTalksSheetImporter($normalizedResponse))->import();

        $this->assertDatabaseCount('public_talks', $count);
    }

    /** @test */
    public function it_creates_memorial_meetings()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[\App\Models\GoogleSheet\GuestTalksSheetImporter::TYPE_COLUMN] === "GM";
        })->count();

        (new GuestTalksSheetImporter($normalizedResponse))->import();

        $this->assertDatabaseCount('memorial_meetings', $count);
    }

    /** @test */
    public function it_special_talks_for_memorials()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[\App\Models\GoogleSheet\GuestTalksSheetImporter::TYPE_COLUMN] === "Sondervortrag";
        })->count();

        (new GuestTalksSheetImporter($normalizedResponse))->import();

        $this->assertDatabaseCount('special_talks', $count);
    }

    /**
     * Helper to get a dummy response from the file
     *
     * @return \Illuminate\Support\Collection
     */
    public function getNormalizedDummyResponse():Collection
    {
        if (empty($this->dummyResponse)) {
            $this->dummyResponse = Normalizer::cleanUp(
                require base_path('tests/Dummies/googlesheets-public-talks-response.php')
            );
        }

        return $this->dummyResponse;
    }
}
