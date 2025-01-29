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
        try {
            $pull_requests = GitHub::pullRequest()->all($this->owner, $this->repo, ['state' => 'open']);
            return ["states"=>true,"data"=>$pull_requests,'message' =>"you git "]; ;
        } catch (\Exception $e) {
            return ["states"=>false,"data"=>"","message" => $e->getMessage()];
        }
    }

    public function categorize_pull_requests(){
        $pull_requests = $this->fetch_pull_requests();

        if (!$pull_requests['states']) {
            return $pull_requests;
        }

    }

}

