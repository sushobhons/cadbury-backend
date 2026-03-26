<?php

use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\VoteController;
use Illuminate\Support\Facades\Route;

Route::post('/upload-photo', [PhotoController::class, 'upload']);
Route::get('/votes/check/{phone}', [VoteController::class, 'check']);
Route::post('/vote', [VoteController::class, 'store']);

