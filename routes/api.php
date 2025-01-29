<?php

use App\Http\Controllers\GitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/get_pull_requests", [GitController::class, "get_pull_requests"]);
