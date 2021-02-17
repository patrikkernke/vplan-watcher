<?php

namespace Tests\Unit\GoogleSheet;

use App\Models\Congress;
use App\Models\GoogleSheet\GuestTalksSheet\ColumnMap;
use App\Models\GoogleSheet\Normalizer;
use App\Models\GoogleSheet\GuestTalksSheet\Importer as SheetImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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

        $publicTalks = collect($normalizedResponse)->filter(function($row) {
            return $row[ColumnMap::TYPE] === "Vortrag";
        });

        (new SheetImporter($normalizedResponse))->import();

        $publicTalks->each(function($talk) {
            $this->assertDatabaseHas('public_talks', [
                'startAt' => Carbon::createFromFormat('d.m.y H:i', $talk[ColumnMap::DATE])->toDateTimeString(),
                'speaker' => $talk[ColumnMap::SPEAKER],
                'disposition' => $talk[ColumnMap::DISPOSITION],
                'topic' => $talk[ColumnMap::TOPIC],
            ]);
        });
    }

    /** @test */
    public function it_creates_memorial_meetings()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[ColumnMap::TYPE] === "GM";
        })->count();

        (new SheetImporter($normalizedResponse))->import();

        $this->assertDatabaseCount('memorial_meetings', $count);
    }

    /** @test */
    public function it_creates_special_talks_for_memorials()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[ColumnMap::TYPE] === "Sondervortrag";
        })->count();

        (new SheetImporter($normalizedResponse))->import();

        $this->assertDatabaseCount('special_talks', $count);
    }

    /** @test */
    public function it_creates_circuit_overseer_talk()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $circuitOverseerTalk = collect($normalizedResponse)->filter(function($row) {
            return $row[ColumnMap::TYPE] === "Dienstwoche";
        });

        (new SheetImporter($normalizedResponse))->import();

        $circuitOverseerTalk->each(function($talk) {
            $this->assertDatabaseHas('public_talks', [
                'startAt' => Carbon::createFromFormat('d.m.y H:i', $talk[ColumnMap::DATE])->toDateTimeString(),
                'speaker' => $talk[ColumnMap::SPEAKER],
                'disposition' => $talk[ColumnMap::DISPOSITION],
                'topic' => $talk[ColumnMap::TOPIC],
            ]);
        });
    }

    /** @test */
    public function it_creates_congresses()
    {
        // Arrange
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[ColumnMap::TYPE] === "Kongress";
        })->count();

        // Act
        (new SheetImporter($normalizedResponse))->import();

        // Assert
        $this->assertDatabaseCount('congresses', $count);
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
