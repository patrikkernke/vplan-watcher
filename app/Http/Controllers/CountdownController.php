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
        $coverImage = 'default.jpg';

        if ($today->isFriday()) {
            $isMeetingToday = true;
            $meetingName = 'Leben- und Dienstzusammenkunft';
            $startTime = $today->copy()->setTime(19, 00);
        }

        if ($today->isSunday()) {
            $isMeetingToday = true;
            $meetingName = 'Zusammenkunft für die Öffentlichkeit';
            $startTime = $today->copy()->setTime(10, 0);
        }

        if ($today->isSameDay('2021-03-27')) {
            $isMeetingToday = true;
            $meetingName = 'Gedächtnismahl';
            $startTime = $today->copy()->setTime(19, 0);
            $coverImage = 'memorial.png';
        }

        return view('countdown', compact(
            'isMeetingToday',
            'meetingName',
            'startTime',
            'coverImage'
        ));
    }
}
