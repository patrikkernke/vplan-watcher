<?php

namespace Tests\Unit\GoogleSheet;

use App\Models\Meeting;
use App\Models\Schedule\Item\CircuitOverseerTalk;
use App\Models\Schedule\Item\PublicTalk;
use App\GoogleSheet\GuestTalksSheet\Column;
use App\GoogleSheet\Normalizer;
use App\GoogleSheet\GuestTalksSheet\Importer as SheetImporter;
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
    public function it_creates_public_talks()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();

        $publicTalks = collect($normalizedResponse)->filter(function($row) {
            return $row[Column::TYPE] === "Vortrag";
        });

        (new SheetImporter($normalizedResponse))->import();

        $publicTalks->each(function($talk) {
            $this->assertDatabaseHas('public_talks', [
                'startAt' => Carbon::createFromFormat('d.m.y H:i', $talk[Column::DATE])->toDateTimeString(),
                'speaker' => $talk[Column::SPEAKER],
                'disposition' => $talk[Column::DISPOSITION],
                'topic' => $talk[Column::TOPIC],
            ]);
        });
    }

    /** @test */
    public function it_creates_memorial_meetings()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[Column::TYPE] === "GM";
        })->count();

        (new SheetImporter($normalizedResponse))->import();

        $meetings = Meeting::where('type', 'Gedächtnismahl')->get();

        $this->assertCount($count, $meetings);

        foreach ($meetings as $meeting) {
            $this->assertCount(1, $meeting->schedule());
            $this->assertInstanceOf(PublicTalk::class, $meeting->schedule()->first());
        }
    }

    /** @test */
    public function it_creates_special_talks_for_memorials()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[Column::TYPE] === "Sondervortrag";
        })->count();

        (new SheetImporter($normalizedResponse))->import();

        $this->assertDatabaseCount('special_talks', $count);
    }

    /** @test */
    public function it_creates_circuit_overseer_public_talk()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $circuitOverseerPublicTalksRaw = collect($normalizedResponse)->filter(function($row) {
            return $row[Column::TYPE] === "Dienstwoche";
        });

        (new SheetImporter($normalizedResponse))->import();

        foreach ($circuitOverseerPublicTalksRaw as $row) {
            $startDate = Carbon::createFromFormat('d.m.y H:i', $row[Column::DATE]);
            $meetings = Meeting::where('startAt', $startDate->toDateTimeString())->get();
            $this->assertCount(1, $meetings);
            $this->assertInstanceOf(CircuitOverseerTalk::class, $meetings->first()->schedule()->first());
        }
    }

    /** @test */
    public function it_creates_congresses()
    {
        // Arrange
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[Column::TYPE] === "Kongress";
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
