<?php

use App\Http\Controllers\GitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/get_pull_requests", [GitController::class, "get_pull_requests"]);
Route::get("/download_files", [GitController::class, "download_files"]);
Route::get("/test", [GitController::class, "test"]);
