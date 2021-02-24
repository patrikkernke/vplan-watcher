<?php


namespace App\Actions;


use App\Models\Meeting;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;

class CreatePdfDataSource
{
    public static function weekendMeetings()
    {
        $meetings = Meeting::orderBy('startAt')->get();

        $remappedMeetings = $meetings->map(function ($meeting) {
            return $meeting->exportForPdfSource();
        });

        Storage::disk('pdf-sources')->put(
            'weekend-meetings.json',
            $remappedMeetings->toJson()
        );
    }
}
