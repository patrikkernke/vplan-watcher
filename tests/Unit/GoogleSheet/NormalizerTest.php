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
        $collection = Normalizer::cleanUp($this->getDummyResponse());

        $this->assertInstanceOf(Collection::class, $collection);
    }

    /** @test */
    public function it_converts_empty_values_to_null()
    {
        $collection = Normalizer::cleanUp($this->getDummyResponse());

        $rowsWithEmptyStrings = $collection->filter(function($row) {
            return in_array("", $row, true);
        });

        $this->assertCount(0, $rowsWithEmptyStrings);
    }

    /** @test */
    public function it_removes_the_header_row()
    {
        $response = $this->getDummyResponse();
        $headerRow = collect($response)->first();

        $collection = Normalizer::cleanUp($response);

        $this->assertFalse($collection->contains($headerRow));
    }

    /** @test */
    public function it_adds_empty_columns_if_they_are_missed()
    {
        $response = $this->getDummyResponse();
        $neededColumnsCount = count(collect($response)->first());

        $collection = Normalizer::cleanUp($response);

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
