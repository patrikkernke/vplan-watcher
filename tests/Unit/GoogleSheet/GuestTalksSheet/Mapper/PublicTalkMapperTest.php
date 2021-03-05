<?php

namespace Tests\Unit\GoogleSheet\GuestTalksSheet\GuestTalksSheet\Mapper;

use App\GoogleSheet\GuestTalksSheet\Column;
use App\GoogleSheet\GuestTalksSheet\Mapper\PublicTalkMapper;
use App\Models\Meeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTalkMapperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_meeting()
    {
        // Arrange
        $dummyRow = $this->dummyRow();
        // Act
        $meeting = PublicTalkMapper::map($dummyRow);
        // Assert
        $this->assertInstanceOf(Meeting::class, $meeting);
    }
    /**
     * @test
     * @dataProvider topicPlaceholders
     *
     * @param $topic
     */
    public function it_replaces_topic_placeholders($topic)
    {
        // Arrange
        $dummyRow = $this->dummyRow();
        $dummyRow[Column::TOPIC] = $topic;
        // Act
        $meeting = PublicTalkMapper::map($dummyRow);
        // Assert
        $publicTalkTopic = $meeting->schedule()[0]->topic;
        $this->assertNull($publicTalkTopic);
    }

    public function topicPlaceholders():array
    {
        return [
            ['///////// Vortragsthema /////////'],
            ['////// Vortragsthema ////'],
            ['///////// VORTRAGSTHEMA FOLGT /////////'],
            ['///// vortragsthema kommt ////'],
            ['Vortragsthema folgt noch'],
        ];
    }

    public function dummyRow()
    {
        return [
            Column::DATE => '01.01.21 10:00',
            Column::CHAIRMAN => 'P. Kernke',
            Column::SPEAKER => 'Robert Koch',
            Column::CONGREGATION => 'Berlin Süd-Ost',
            Column::DISPOSITION => '120',
            Column::TOPIC => 'Was ist wirklich wichtig?',
            Column::READER => 'J. Krönig',
        ];
    }
}
