<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IoTController;

Route::post('/iot/alert', [IoTController::class, 'receiveAlert']);
Route::post('/device/ping-command', [IoTController::class, 'pollPing']);
Route::post('/device/telemetry', [IoTController::class, 'telemetry']);