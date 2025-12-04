<?php

use App\Http\Controllers\ApiTestController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[CalendarController::class,'index']);
Route::post('/add-event',[CalendarController::class,'addEvent']);

Route::get('/multi',[CalendarController::class,'indexMulti']);
Route::post('/add-event-multi',[CalendarController::class,'addEvents']);

Route::get('/api',[ApiTestController::class,'test']);