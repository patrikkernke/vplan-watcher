<?php

namespace Tests\Unit\GoogleSheet;

use App\GoogleSheet\Normalizer;
use Illuminate\Support\Collection;
use Tests\TestCase;

class NormalizerTest extends TestCase
{
    /**
     * @test
     * @dataProvider googleSheetResponses
     */
    public function it_returns_a_collection($response)
    {
        // Act
        $collection = Normalizer::cleanUp($response);
        // Assert
        $this->assertInstanceOf(Collection::class, $collection);
    }

    /**
     * @test
     * @dataProvider googleSheetResponses
     */
    public function it_converts_empty_values_to_null($response)
    {
        // Act
        $collection = Normalizer::cleanUp($response);
        // Assert
        $rowsWithEmptyStrings = $collection->filter(function($row) {
            return in_array("", $row, true);
        });
        $this->assertCount(0, $rowsWithEmptyStrings);
    }

    /**
     * @test
     * @dataProvider googleSheetResponses
     */
    public function it_trims_string_values($response)
    {
        array_push($response, ['    column1   ', 'column2 ', '     column3',]);
        // Act
        $collection = Normalizer::cleanUp($response);
        // Assert
        $this->assertEquals('column1', $collection->last()[0]);
        $this->assertEquals('column2', $collection->last()[1]);
        $this->assertEquals('column3', $collection->last()[2]);
    }

    /**
     * @test
     * @dataProvider googleSheetResponses
     */
    public function it_removes_the_header_row($response)
    {
        // Arrange
        $headerRow = collect($response)->first();
        // Act
        $collection = Normalizer::cleanUp($response);
        // Assert
        $this->assertFalse($collection->contains($headerRow));
    }

    /**
     * @test
     * @dataProvider googleSheetResponses
     */
    public function it_adds_empty_columns_if_they_are_missed($response)
    {
        // Arrange
        $neededColumnsCount = count(collect($response)->first());
        // Act
        $collection = Normalizer::cleanUp($response);
        // Assert
        $rowsWithToFewColumns = $collection->filter(function($row) use ($neededColumnsCount) {
           return count($row) < $neededColumnsCount;
        });
        $this->assertCount(0, $rowsWithToFewColumns);
    }

    public function googleSheetResponses()
    {
        return [
            'Gastvorträge' => [require 'tests/Dummies/googlesheets-response-gastvortrage.php'],
            'Treffpunkte' => [require 'tests/Dummies/googlesheets-response-treffpunkte.php'],
        ];
    }
}
