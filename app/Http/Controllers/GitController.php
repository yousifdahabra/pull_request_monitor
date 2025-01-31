<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GitService;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Sheets;

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

        $zip = new ZipArchive;
        if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

            $files = [
                'old_pull_requests.txt',
                'required_labels.txt',
                'review_required_pull_requests.txt'
            ];

            foreach ($files as $file) {
                $file_path = storage_path("app/private/{$file}");

                if (file_exists($file_path)) {
                    $zip->addFile($file_path, $file);
                }
            }

            $zip->close();
        } else {
            return response()->json(
                ["states"=> false,
                "data" =>'',
                'message' =>"could not create zip file"]
                , 500);
        }

        return response()->download($zip_path);
    }
    public function test(){
        $values = Sheets::spreadsheet('1u1r1hF5Kp57yf3hg1IlKw33u4oEIuEjm88MTqW1XZe8')->sheet('Sheet 1')->all();
        return response()->json(
            ["states"=> false,
            "data" =>$values,
            'message' =>"google"]
            , 200);

    }
}
