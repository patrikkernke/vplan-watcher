<?php

use App\Http\Controllers\Api\PdfController;
use App\Http\Controllers\Api\ShortcutsController;
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

Route::get('pdf/data/away-speaker', [PdfController::class, 'awaySpeaker'])
    ->middleware('auth:sanctum')
    ->name('pdf.data.away-speaker');

Route::get('pdf/data/service-meetings/{year?}/{month?}', [PdfController::class, 'serviceMeetings'])
    ->middleware('auth:sanctum')
    ->name('pdf.data.service-meetings');

Route::get('pdf/data/weekend-meetings', [PdfController::class, 'weekendMeetings'])
    ->middleware('auth:sanctum')
    ->name('pdf.data.weekend-meetings');

Route::get('/shortcuts/meetings/next/weekend-meeting', [ShortcutsController::class, 'nextWeekendMeeting'])
    ->middleware('auth:sanctum');

use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use App\Models\ServiceMeeting;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\Location;


Route::get('/calendar', function () {

    $calendar = new Calendar();
    $serviceMeetings = ServiceMeeting::onlyForCongregation()->orderBy('start_at')->get();

    $serviceMeetings->each(function($meeting) use ($calendar) {
        $event = new Event();
        $event->setSummary('Treffpunkt')
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

        if ($meeting->fieldServiceGroup->name !== 'Niederbieber') {
            return true; // continue
        }

        $event = new Event();
        $name = Str::of( $meeting->fieldServiceGroup->name);
        $zoom = config('zoom.field_service_group.' . $name->lower()->slug('_'));
        $event->setSummary('Treffpunkt ' . $name)
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

        $calendar->addEvent($event);
    });

    return (new CalendarFactory())->createCalendar($calendar);

})->middleware('auth:easy');
