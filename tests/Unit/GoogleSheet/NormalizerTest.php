<?php

namespace Tests\Unit\GoogleSheet;

use App\GoogleSheet\Normalizer;
use Illuminate\Support\Collection;
use Tests\TestCase;

class NormalizerTest extends TestCase
{
    /**
     * @var array
     */
    private $dummyResponse = [];

    /** @test */
    public function it_returns_a_collection()
    {
        // Arrange
        $response = $this->getDummyResponse();
        // Act
        $collection = Normalizer::cleanUp($response);
        // Assert
        $this->assertInstanceOf(Collection::class, $collection);
    }

    /** @test */
    public function it_converts_empty_values_to_null()
    {
        // Arrange
        $response = $this->getDummyResponse();
        // Act
        $collection = Normalizer::cleanUp($response);
        // Assert
        $rowsWithEmptyStrings = $collection->filter(function($row) {
            return in_array("", $row, true);
        });
        $this->assertCount(0, $rowsWithEmptyStrings);
    }

    /** @test */
    public function it_trims_string_values()
    {
        // Arrange
        $response = $this->getDummyResponse();
        array_push($response, ['    column1   ', 'column2 ', '     column3',]);
        // Act
        $collection = Normalizer::cleanUp($response);
        // Assert
        $this->assertEquals('column1', $collection->last()[0]);
        $this->assertEquals('column2', $collection->last()[1]);
        $this->assertEquals('column3', $collection->last()[2]);
    }

    /** @test */
    public function it_removes_the_header_row()
    {
        // Arrange
        $response = $this->getDummyResponse();
        $headerRow = collect($response)->first();
        // Act
        $collection = Normalizer::cleanUp($response);
        // Assert
        $this->assertFalse($collection->contains($headerRow));
    }

    /** @test */
    public function it_adds_empty_columns_if_they_are_missed()
    {
        // Arrange
        $response = $this->getDummyResponse();
        $neededColumnsCount = count(collect($response)->first());
        // Act
        $collection = Normalizer::cleanUp($response);
        // Assert
        $rowsWithToFewColumns = $collection->filter(function($row) use ($neededColumnsCount) {
           return count($row) < $neededColumnsCount;
        });
        $this->assertCount(0, $rowsWithToFewColumns);
    }

    /**
     * Helper to get a dummy response from the file
     * @return array
     */
    public function getDummyResponse():array
    {
        if (empty($this->dummyResponse)) {
            $this->dummyResponse = require base_path('tests/Dummies/googlesheets-public-talks-response.php');
        }

        return $this->dummyResponse;
    }
}
