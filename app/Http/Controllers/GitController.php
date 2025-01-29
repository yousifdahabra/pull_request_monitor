<?php

namespace App\Http\Controllers;

use GrahamCampbell\GitHub\GitHubServiceProvider;
use Illuminate\Http\Request;

class GitController extends Controller{

    public function get_pull_requests(Request $request){

        $repo_url = $request->input('repo_url');
        if (!$repo_url) {
            return response()->json([
                'status' => false,
                'message' => 'Repository URL is required',
                "data" => ""
            ], 400);
        }
        $github_service = new GitHubServiceProvider($repo_url);

        return response()->json([
            'status' => true,
            'message' => 'get pull requests done',
            "data" => ""
        ], 200);

    }
}
