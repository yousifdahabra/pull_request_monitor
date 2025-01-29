<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GitController extends Controller{

    public function get_pull_requests(Request $request){

        $repoUrl = $request->input('repo_url');

        return response()->json([
            'status' => true,
            'message' => 'get pull requests done',
        ], 200);

    }
}
