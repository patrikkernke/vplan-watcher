<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AwaySpeakerResource;
use App\Http\Resources\MeetingResource;
use App\Http\Resources\ServiceMeetingResource;
use App\Models\AwaySpeaker;
use App\Models\Meeting;
use App\Models\ServiceMeeting;

class PdfController extends Controller
{
    public function awaySpeaker()
    {
        $speakers = AwaySpeaker::orderBy('lastname')->orderBy('firstname')->get();
        return AwaySpeakerResource::collection($speakers);
    }

    public function weekendMeetings()
    {
        $meetings = Meeting::after(now()->subWeeks(4))->orderBy('start_at')->get();

        return MeetingResource::collection($meetings);
    }

    public function serviceMeetings($year = null, $month = null)
    {
        $days = collect([]);
        $now = now();

        $year = $year ?? $now->year;
        $month = $month ?? $now->month;

        $now->setYear($year);
        $now->setMonth($month);

        for ($i = 1; $i <= $now->daysInMonth; $i++){
            $meetings = ServiceMeeting::whereYear('start_at', $now->year)
                ->whereMonth('start_at', $now->month)
                ->whereDay('start_at', $i)
                ->get();

            $days[$i] = ServiceMeetingResource::collection($meetings);
        }

        return response()->json([
            'reference' => [
                'year' => intval($year),
                'month' => intval($month),
            ],
            'data' => $days
        ]);

    }
}
