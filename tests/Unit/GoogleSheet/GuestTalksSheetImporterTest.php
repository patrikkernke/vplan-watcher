<?php

namespace Tests\Unit\GoogleSheet;

use App\Models\Congress;
use App\Models\GoogleSheet\Normalizer;
use App\Models\GoogleSheet\GuestTalksSheetImporter as SheetImporter;
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
            return $row[SheetImporter::TYPE_COLUMN] === "Vortrag";
        });

        (new SheetImporter($normalizedResponse))->import();

        $publicTalks->each(function($talk) {
            $this->assertDatabaseHas('public_talks', [
                'startAt' => Carbon::createFromFormat('d.m.y H:i', $talk[SheetImporter::DATE_COLUMN])->toDateTimeString(),
                'speaker' => $talk[SheetImporter::SPEAKER_COLUMN],
                'disposition' => $talk[SheetImporter::DISPOSITION_COLUMN],
                'topic' => $talk[SheetImporter::TOPIC_COLUMN],
            ]);
        });
    }

    /** @test */
    public function it_creates_memorial_meetings()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[SheetImporter::TYPE_COLUMN] === "GM";
        })->count();

        (new SheetImporter($normalizedResponse))->import();

        $this->assertDatabaseCount('memorial_meetings', $count);
    }

    /** @test */
    public function it_creates_special_talks_for_memorials()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[SheetImporter::TYPE_COLUMN] === "Sondervortrag";
        })->count();

        (new SheetImporter($normalizedResponse))->import();

        $this->assertDatabaseCount('special_talks', $count);
    }

    /** @test */
    public function it_creates_circuit_overseer_talk()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $circuitOverseerTalk = collect($normalizedResponse)->filter(function($row) {
            return $row[SheetImporter::TYPE_COLUMN] === "Dienstwoche";
        });

        (new SheetImporter($normalizedResponse))->import();

        $circuitOverseerTalk->each(function($talk) {
            $this->assertDatabaseHas('public_talks', [
                'startAt' => Carbon::createFromFormat('d.m.y H:i', $talk[SheetImporter::DATE_COLUMN])->toDateTimeString(),
                'speaker' => $talk[SheetImporter::SPEAKER_COLUMN],
                'disposition' => $talk[SheetImporter::DISPOSITION_COLUMN],
                'topic' => $talk[SheetImporter::TOPIC_COLUMN],
            ]);
        });
    }

    /** @test */
    public function it_creates_congresses()
    {
        // Arrange
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[SheetImporter::TYPE_COLUMN] === "Kongress";
        })->count();

        ray(collect($normalizedResponse)->filter(function($row) {
            return $row[SheetImporter::TYPE_COLUMN] === "Kongress";
        }));

        // Act
        (new SheetImporter($normalizedResponse))->import();

        ray(Congress::all()->toArray());

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
