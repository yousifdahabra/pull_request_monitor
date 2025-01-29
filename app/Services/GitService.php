<?php

namespace App\Services;

class GitService{

    protected $owner;
    protected $repo;

    public function __construct($repo_url){
        $this->extract_repo_details($repo_url);
    }

    private function extract_repo_details($repo_url){
        $parts = parse_url($repo_url);
        $path = trim($parts['path'], '/');
        [$this->owner, $this->repo] = explode('/', $path);
    }

    public function fetch_pull_requests(){
    }

    public function categorize_pull_requests(){
    }

}

