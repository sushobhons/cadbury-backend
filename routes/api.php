<?php

use App\Http\Controllers\Api\PhotoController;
use Illuminate\Support\Facades\Route;

Route::post('/upload-photo', [PhotoController::class, 'upload']);

