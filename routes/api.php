<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use App\Models\ServiceMeeting;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\Location;
use Laravel\Sanctum\PersonalAccessToken;

Route::get('/test', function (Request $request) {
    return 'Test';
})->middleware('auth:easy');

Route::get('/calendar', function () {

    $calendar = new Calendar();
    $serviceMeetings = ServiceMeeting::onlyForCongregation()->orderBy('start_at')->get();

    $serviceMeetings->each(function($meeting) use ($calendar) {
        $event = new Event();
        $event->setSummary('TP Versammlung')
            ->setLocation(new Location('Zoom'))
            ->setDescription(
                'ID ' . config('zoom.congregation.service_meeting.id' ) . PHP_EOL .
                'Password ' . config('zoom.congregation.service_meeting.password' ) . PHP_EOL .
                config('zoom.congregation.service_meeting.link' )
            )
            ->setOccurrence(
                new TimeSpan(
                    new DateTime($meeting->start_at->copy(), false),
                    new DateTime($meeting->start_at->copy()->addMinutes(15), false)
                )
            );

        $calendar->addEvent($event);
    });

    $serviceMeetings = ServiceMeeting::onlyForFieldServiceGroup()->orderBy('start_at')->get();
    $serviceMeetings->each(function($meeting) use ($calendar) {
        $event = new Event();
        $name = Str::of( $meeting->fieldServiceGroup->name);
        $zoom = config('zoom.field_service_group.' . $name->lower()->slug('_'));
        $event->setSummary('TP ' . $name)
            ->setLocation(new Location('Zoom'))
            ->setDescription(
                'ID ' . $zoom['id'] . PHP_EOL .
                'Password ' . $zoom['password'] . PHP_EOL .
                $zoom['link']
            )
            ->setOccurrence(
                new TimeSpan(
                    new DateTime($meeting->start_at->copy(), false),
                    new DateTime($meeting->start_at->copy()->addMinutes(15), false)
                )
            );

//        $calendar->addEvent($event);
    });


    return (new CalendarFactory())->createCalendar($calendar);
})->middleware('auth:easy');
