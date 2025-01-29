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
            return ["states"=>true,"data"=>$pull_requests,'message' =>"git request successfully"];
        } catch (\Exception $e) {
            return ["states"=>false,"data"=>"","message" => $e->getMessage()];
        }
    }

    public function categorize_pull_requests(){
        $pull_requests = $this->fetch_pull_requests();

        if (!$pull_requests['states']) {
            return $pull_requests;
        }
        $old_pull_requests = [];
        $review_required_pull_requests = [];

        foreach ($pull_requests as $pull_request) {
            $created_at = strtotime($pull_request['created_at']);
            $days = (time() - $created_at) / 86400;
            if ($days > 30) {
                $old_pull_requests[] = "PR #{$pull_request['number']}: {$pull_request['title']} ({$pull_request['html_url']})";
            }
            if (empty($pull_request['requested_reviewers'])) {
                $review_required_pull_requests[] = "PR #{$pull_request['number']}: {$pull_request['title']} ({$pull_request['html_url']})";
            }
        }

    }

}

