<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GitController extends Controller
{
    public function test(){
        return response()->json([
            'status' => true,
            'message' => 'test done',
        ], 200);

    }
}
