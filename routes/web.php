<?php

use App\Http\Controllers\CountdownController;
//use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$host = parse_url(config('app.url'), PHP_URL_HOST);
Route::domain('countdown.' . $host)->group(function () {
    Route::get('/', CountdownController::class);
});


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/pdf-plan/oeffentliche-zusammenkuenfte', [PdfController::class, 'weekendMeetingSchedule']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
