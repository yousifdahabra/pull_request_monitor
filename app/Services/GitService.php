<?php

namespace App\Services;

class GitService{

    public function __construct($repo_url){

    }

    private function extract_repo_details($repo_url){
        $parts = parse_url($repo_url);
        $path = trim($parts['path'], '/');
    }

}

