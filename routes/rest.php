<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestApiController;

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::GET('/navtraxx/all_route_planning', [RestApiController::class, 'all_route_planning']);
});
