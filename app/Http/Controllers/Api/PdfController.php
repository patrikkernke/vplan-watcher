<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AwaySpeakerResource;
use App\Http\Resources\MeetingResource;
use App\Models\AwaySpeaker;
use App\Models\Meeting;

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
}
