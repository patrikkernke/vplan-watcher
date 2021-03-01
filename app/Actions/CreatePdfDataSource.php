<?php


namespace App\Actions;


use App\Models\Congress;
use App\Models\Meeting;
use App\Models\ServiceMeeting;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;

class CreatePdfDataSource
{
    public static function forWeekendMeetings()
    {
        $meetings = Meeting::after(now()->subWeeks(4))->orderBy('start_at')->get();

        $remappedMeetings = $meetings->map(function ($meeting) {
            return $meeting->exportForPdfSource();
        });

        Storage::disk('pdf-sources')->put(
            'weekend-meetings.json',
            $remappedMeetings->toJson()
        );
    }

    public static function forServiceMeetings()
    {
        $meetings = ServiceMeeting::after(now()->subWeeks(4))->orderBy('start_at')->get();


        $remappedMeetings = $meetings->mapToGroups(function($meeting) {
            $date = $meeting->start_at->format('Y-m-d');
            return [$date => $meeting->exportForPdfSource()];
        });

        Storage::disk('pdf-sources')->put(
            'service-meetings.json',
            $remappedMeetings->toJson()
        );
    }
}
