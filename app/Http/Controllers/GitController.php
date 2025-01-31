<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GitService;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

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
        $github_service = new GitService($repo_url);
        $result = $github_service->save_pull_requests();

        if(!$result['states']){
            return response()->json($result, 400);
        }

        return response()->json( $result, 200);

    }
    public function download_files(){
        $zip_file_name = 'pull_requests.zip';
        $zip_path = storage_path("app/private/{$zip_file_name}");


        return response()->download($zip_path);
    }

}
