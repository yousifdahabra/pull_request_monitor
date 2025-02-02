<?php

use App\Http\Controllers\GitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

Route::post("/get_pull_requests", [GitController::class, "get_pull_requests"]);
Route::get("/download_files", [GitController::class, "download_files"]);

Route::get('/sheets', [GoogleController::class, 'access_google_sheet']);
