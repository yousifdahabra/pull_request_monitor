<?php

use App\Http\Controllers\GitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/test", [GitController::class, "test"]);
