<?php

namespace Tests\Unit\GoogleSheet\ServiceMeetingsSheet;

use App\GoogleSheet\Normalizer;
use App\GoogleSheet\ServiceMeetingsSheet\Column;
use App\GoogleSheet\ServiceMeetingsSheet\SheetImporter;
use App\Models\FieldServiceGroup;
use App\Models\ServiceMeeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Str;
use Tests\TestCase;

class SheetImporterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection
     */
    private $dummyResponse;

    /** @test */
    public function it_has_connection_data_for_the_belonging_google_sheet()
    {
        // Assert
        $this->assertNotEmpty(SheetImporter::SPREADSHEET_ID);
        $this->assertNotEmpty(SheetImporter::SHEETNAME);
        $this->assertNotEmpty(SheetImporter::RANGE);
    }

    /** @test */
    public function it_imports_service_meetings_for_congregation()
    {
        // Arrange
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = 0;
        collect($normalizedResponse)->each(function ($row) use (&$count) {
            $time1 = Str::of($row[Column::TIME_1]);
            if ($time1->isNotEmpty() && ! $time1->contains('DW'))
                $count++;

            $time2 = Str::of($row[Column::TIME_2]);
            if ($time2->isNotEmpty() && ! $time2->contains('DW')) $count++;
        });
        // Act
        (new SheetImporter($normalizedResponse))->import();
        // Assert
        $meetings = ServiceMeeting::onlyForCongregation()->get();
        $this->assertCount($count, $meetings);
    }

    /** @test */
    public function it_imports_service_meetings_for_service_week()
    {
        // Arrange
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = 0;
        collect($normalizedResponse)->each(function ($row) use (&$count) {
            $time1 = Str::of($row[Column::TIME_1]);
            if ($time1->isNotEmpty() && $time1->contains('DW')) $count++;

            $time2 = Str::of($row[Column::TIME_2]);
            if ($time2->isNotEmpty() && $time2->contains('DW')) $count++;
        });
        // Act
        (new SheetImporter($normalizedResponse))->import();
        // Assert
        $meetings = ServiceMeeting::onlyForServiceWeek()->get();
        $this->assertCount($count, $meetings);
    }

    /** @test */
    public function it_imports_service_meetings_without_time_correctly()
    {
        // Arrange
        $meetingData = ['31.03.21', 'Mi.', 'DW', 'U. Ackermann', 'DW', 'U. Ackermann'];
        $meetingData[Column::TIME_1] = 'DW';
        $meetingData[Column::TIME_2] = '10:00 DW';
        $normalizedResponse = Normalizer::cleanUp([
            [
                'Datum', '', 'Zeit', 'Leiter', 'Zeit', 'Leiter', '',
                'Irlich', 'Bendorf 1', 'Niederbieber', 'Neuwied 1', 'Bendorf 2', 'Türkisch', 'Neuwied 2'
            ],
            $meetingData
        ]);
        // Act
        (new SheetImporter($normalizedResponse))->import();
        // Assert
        $meetings = (ServiceMeeting::onlyForServiceWeek()->get());
        $this->assertCount(2, $meetings);
        $this->assertEquals('00:00', $meetings[0]->start_at->format('H:i'));
        $this->assertEquals('10:00', $meetings[1]->start_at->format('H:i'));
    }

    /** @test */
    public function it_creates_required_field_service_groups()
    {
        // Arrange
        $normalizedResponse = $this->getNormalizedDummyResponse();
        // Act
        (new SheetImporter($normalizedResponse))->import();
        // Assert
        $this->assertCount(7, FieldServiceGroup::all());
    }

    /** @test */
    public function it_imports_service_meetings_for_field_service_groups()
    {
        // Arrange
        $normalizedResponse = $this->getNormalizedDummyResponse();
        $count = 0;
        collect($normalizedResponse)->each(function ($row) use (&$count) {
            collect([
                Column::IRLICH,
                Column::BENDORF_1,
                Column::NIEDERBIEBER,
                Column::NEUWIED_1,
                Column::BENDORF_2 ,
                Column::TUERKISCH ,
                Column::NEUWIED_2 ,
            ])->each(function($column) use ($row, &$count) {
                $time = Str::of($row[$column]);
                if ($time->isNotEmpty()) $count++;
            });
        });
        // Act
        (new SheetImporter($normalizedResponse))->import();
        // Assert
        $meetings = ServiceMeeting::onlyForFieldServiceGroup()->get();
        $this->assertCount($count, $meetings);
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
                require base_path('tests/Dummies/googlesheets-response-treffpunkte.php')
            );
        }

        return $this->dummyResponse;
    }
}
