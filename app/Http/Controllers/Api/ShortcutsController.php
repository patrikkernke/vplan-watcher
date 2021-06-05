<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Schedule\Item\PublicTalk;
use App\Models\Schedule\Item\WatchtowerStudy;

class ShortcutsController extends Controller
{
    public function nextWeekendMeeting()
    {
        $meeting = Meeting::after(now())->orderBy('start_at')->first();

        return response()->json([ 'data' => $this->exportForApi($meeting)]);
    }

    private function exportForApi($meeting)
    {
        if (is_null($meeting)) return null;

        $schedule = $meeting->schedule();
        $publicTalk = $schedule->whereInstanceOf(PublicTalk::class)->first();
        $watchtowerStudy = $schedule->whereInstanceOf(WatchtowerStudy::class)->first();

        return [
            'start_at' => $meeting->start_at->toDateTimeString(),
            'type' => $meeting->type,
            'chairman' => $meeting->chairman,
            'publicTalk' => [
                'speaker' => optional($publicTalk)->speaker,
                'disposition' => optional($publicTalk)->disposition,
                'topic' => optional($publicTalk)->topic,
                'congregation' => optional($publicTalk)->congregation
            ],
            'watchtowerStudy' => [
                'conductor' => optional($watchtowerStudy)->conductor,
                'reader' => optional($watchtowerStudy)->reader,
            ],
        ];
    }
}
