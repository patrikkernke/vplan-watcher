<?php

namespace Tests\Unit\GoogleSheet;

use App\Models\Meeting;
use App\GoogleSheet\GuestTalksSheet\Column;
use App\Models\Schedule\Item\CircuitOverseerTalk;
use App\Models\Schedule\Item\Congress;
use App\Models\Schedule\Item\PublicTalk;
use App\GoogleSheet\Normalizer;
use App\GoogleSheet\GuestTalksSheet\SheetImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SheetImporterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array
     */
    private $dummyResponse = [];

    /** @test */
    public function it_has_connection_data_for_the_belonging_google_sheet()
    {
        // Assert
        $this->assertNotEmpty(SheetImporter::SPREADSHEET_ID);
        $this->assertNotEmpty(SheetImporter::SHEETNAME);
        $this->assertNotEmpty(SheetImporter::RANGE);
    }

    /** @test */
    public function it_imports_public_talks()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();

        $publicTalks = collect($normalizedResponse)->filter(function($row) {
            return $row[Column::TYPE] === "Vortrag";
        });

        (new SheetImporter($normalizedResponse))->import();

        //** todo@pk -> Test über Objekt statt Datenbank */
        $publicTalks->each(function($talk) {
            $this->assertDatabaseHas('public_talks', [
                'startAt' => Carbon::createFromFormat('d.m.y H:i', $talk[Column::DATE])->toDateTimeString(),
                'speaker' => $talk[Column::SPEAKER],
                'disposition' => $talk[Column::DISPOSITION],
            ]);
        });
    }

    /** @test */
    public function it_imports_memorial_meetings()
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
    public function it_imports_special_talks_for_memorials()
    {
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[Column::TYPE] === "Sondervortrag";
        })->count();

        (new SheetImporter($normalizedResponse))->import();

        $this->assertDatabaseCount('special_talks', $count);
    }

    /** @test */
    public function it_imports_circuit_overseer_public_talk()
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
    public function it_imports_congresses()
    {
        // Arrange
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = collect($normalizedResponse)->filter(function($row) {
            return $row[Column::TYPE] === "Kongress";
        })->count();
        // Act
        (new SheetImporter($normalizedResponse))->import();
        // Assert
        $meetings = Meeting::where('type', 'Kongress')->get();
        $this->assertCount($count, $meetings);
        foreach ($meetings as $meeting) {
            $this->assertCount(1, $meeting->schedule());
            $this->assertInstanceOf(Congress::class, $meeting->schedule()->first());
        }
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
                require base_path('tests/Dummies/googlesheets-response-gastvortrage.php')
            );
        }

        return $this->dummyResponse;
    }
}
