<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeetingResource;
use App\Models\Meeting;

class ShortcutsController extends Controller
{
    public function nextWeekendMeeting()
    {
        $meeting = Meeting::after(now())->orderBy('start_at')->first();

        return $meeting ?
            new MeetingResource($meeting)
            : null;
    }
}
