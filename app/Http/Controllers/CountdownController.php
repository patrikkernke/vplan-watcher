<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class CountdownController extends Controller
{
    public function __invoke()
    {
        $today = now();

        $isMeetingToday = false;
        $meetingName = 'Keine Zusammenkunft';
        $startTime = null;

        if ($today->isFriday()) {
            $isMeetingToday = true;
            $meetingName = 'Leben- und Dienstzusammenkunft';
            $startTime = $today->copy()->setTime(16, 45);
        }

        if ($today->isSunday()) {
            $isMeetingToday = true;
            $meetingName = 'Zusammenkunft für die Öffentlichkeit';
            $startTime = $today->copy()->setTime(10, 0);
        }

        return view('countdown', compact(
            'isMeetingToday',
            'meetingName',
            'startTime'
        ));
    }
}
