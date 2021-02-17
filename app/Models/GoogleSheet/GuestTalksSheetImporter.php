<?php


namespace App\Models\GoogleSheet;


use App\Models\Congress;
use App\Models\MemorialMeeting;
use App\Models\PublicTalk;
use App\Models\SpecialTalk;
use App\Models\WatchtowerStudy;
use App\Models\WeekendMeeting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class GuestTalksSheetImporter
{
    const DATE_COLUMN = 0;
    const TYPE_COLUMN = 1;
    const SPEAKER_COLUMN = 2;
    const CONGREGATION_COLUMN = 3;
    const DISPOSITION_COLUMN = 4;
    const TOPIC_COLUMN = 5;
    const CHAIRMAN_COLUMN = 6;
    const READER_COLUMN = 7;
    const AWAY_SPEAKER_COLUMN = 8;

    private $rawValues;

    public function __construct($rawValues)
    {
        $this->rawValues = collect($rawValues);
    }

    public function import()
    {
        $this->rawValues->each(function($row) {

            if (empty($row[self::DATE_COLUMN])) {
                return false;
            }

            if ($row[self::TYPE_COLUMN] == 'Vortrag') {
                $meeting = new WeekendMeeting();
                $meeting->startAt = Carbon::createFromFormat('d.m.y H:i', $row[self::DATE_COLUMN])->toDateTimeString();
                $meeting->chairman = $row[self::CHAIRMAN_COLUMN];
                $meeting->save();

                $publicTalk = new PublicTalk();
                $publicTalk->startAt = $meeting->startAt;
                $publicTalk->speaker = $row[self::SPEAKER_COLUMN];
                $publicTalk->congregation = $row[self::CONGREGATION_COLUMN];
                $publicTalk->disposition = $row[self::DISPOSITION_COLUMN];
                $publicTalk->topic = $row[self::TOPIC_COLUMN];
                $meeting->publicTalks()->save($publicTalk)->toArray();

                $watchtowerStudy = new WatchtowerStudy();
                $watchtowerStudy->startAt = $meeting->startAt->copy()->addMinutes(35);
                $watchtowerStudy->reader = $row[self::READER_COLUMN];
                $meeting->watchtowerStudy()->save($watchtowerStudy);
            }

            if ($row[self::TYPE_COLUMN] == 'GM') {
                $meeting = new MemorialMeeting();
                $meeting->startAt = Carbon::createFromFormat('d.m.y H:i', $row[self::DATE_COLUMN])->toDateTimeString();
                $meeting->chairman = $row[self::CHAIRMAN_COLUMN];
                $meeting->speaker = $row[self::SPEAKER_COLUMN];
                $meeting->disposition = $row[self::DISPOSITION_COLUMN];
                $meeting->topic = $row[self::TOPIC_COLUMN];
                $meeting->save();
            }

            if ($row[self::TYPE_COLUMN] == 'Sondervortrag') {
                $meeting = new WeekendMeeting();
                $meeting->startAt = Carbon::createFromFormat('d.m.y H:i', $row[self::DATE_COLUMN])->toDateTimeString();
                $meeting->chairman = $row[self::CHAIRMAN_COLUMN];
                $meeting->save();

                $specialTalk = new SpecialTalk();
                $specialTalk->startAt = $meeting->startAt;
                $specialTalk->speaker = $row[self::SPEAKER_COLUMN];
                $specialTalk->congregation = $row[self::CONGREGATION_COLUMN];
                $specialTalk->disposition = $row[self::DISPOSITION_COLUMN];
                $specialTalk->topic = $row[self::TOPIC_COLUMN];
                $meeting->specialTalk()->save($specialTalk)->toArray();

                $watchtowerStudy = new WatchtowerStudy();
                $watchtowerStudy->startAt = $meeting->startAt->copy()->addMinutes(35);
                $watchtowerStudy->reader = $row[self::READER_COLUMN];
                $meeting->watchtowerStudy()->save($watchtowerStudy);
            }

            if ($row[self::TYPE_COLUMN] == 'Dienstwoche') {
                $meeting = new WeekendMeeting();
                $meeting->startAt = Carbon::createFromFormat('d.m.y H:i', $row[self::DATE_COLUMN])->toDateTimeString();
                $meeting->chairman = $row[self::CHAIRMAN_COLUMN];
                $meeting->save();

                $publicTalk = new PublicTalk();
                $publicTalk->startAt = $meeting->startAt;
                $publicTalk->speaker = $row[self::SPEAKER_COLUMN];
                $publicTalk->congregation = $row[self::CONGREGATION_COLUMN];
                $publicTalk->disposition = $row[self::DISPOSITION_COLUMN];
                $publicTalk->topic = $row[self::TOPIC_COLUMN];
                $meeting->publicTalks()->save($publicTalk)->toArray();

                $watchtowerStudy = new WatchtowerStudy();
                $watchtowerStudy->startAt = $meeting->startAt->copy()->addMinutes(35);
                $meeting->watchtowerStudy()->save($watchtowerStudy);
            }

            if ($row[self::TYPE_COLUMN] == 'Kongress') {
                $congress = new Congress();
                $congress->startAt = Carbon::createFromFormat('d.m.y H:i', $row[self::DATE_COLUMN])->toDateTimeString();
                $congress->motto_id = $row[self::DISPOSITION_COLUMN];
                $congress->motto = $row[self::TOPIC_COLUMN];
                $congress->part = $row[self::CONGREGATION_COLUMN];
                $congress->save();
            }

            return true;
        });
    }
}
