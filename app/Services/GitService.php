<?php

namespace App\Services;

use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Support\Facades\Storage;

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
        $all_pull_requests = [];
        $is_start = true;
        $page = 1;
        try {
            while($is_start){
                $pull_requests = GitHub::pullRequest()->all($this->owner, $this->repo, ['state' => 'open','per_page' => 100,'page' => $page]);
                if(empty($pull_requests) || $page > 1){
                    $is_start = false;
                }else{
                    $all_pull_requests = array_merge($all_pull_requests, $pull_requests);
                }
                $page++;
            }
            return ["states"=>true,"data"=>$all_pull_requests,'message' =>"git request successfully"];
        } catch (\Exception $e) {
            return ["states"=>false,"data"=>$all_pull_requests,"message" => $e->getMessage()];
        }
    }

    public function categorize_pull_requests(){
        $pull_requests = $this->fetch_pull_requests();

        if (!$pull_requests['states']) {
            return $pull_requests;
        }
        $pull_requests = $pull_requests['data'];

        if (!is_array($pull_requests) || empty($pull_requests)) {
            return ["states" => false, "data" => [], "message" => "Unexpected API response"];
        }

        $filter_by = [];
        $count = Count($pull_requests);
        foreach ($pull_requests as $pull_request) {
            $created_at = strtotime($pull_request['created_at']);
            $days = floor((time() - $created_at) / 86400);

            if ($days > 14) {
                $filter_by['old_pull_requests'][] = $this->format_pull_request($pull_request);
            }

            if (empty($pull_request['requested_reviewers'])) {
                $filter_by['empty_review_required_pull_requests'][] = $this->format_pull_request($pull_request);
            }

            if (!empty($pull_request['requested_reviewers'])) {
                $filter_by['required_review_pull_requests'][] = $this->format_pull_request($pull_request);
            }

            if (empty($pull_request['labels'])) {
                $filter_by['required_labels'][] = $this->format_pull_request($pull_request);
            }

            if ($this->is_review_successful($pull_request)) {
                $filter_by['review_success_pull_requests'][] = $this->format_pull_request($pull_request);
            }

        }
        return [
            "states"=>true,
            "data" => $filter_by,
            "count" => $count,
            'message' =>"git request successfully"
        ];
    }
    public function save_pull_requests(){
        $categorize_pull_requests = $this->categorize_pull_requests();

        if (!$categorize_pull_requests['states']) {
            return $categorize_pull_requests;
        }
        $categorize_pull_requests = $categorize_pull_requests['data'];
        foreach ($categorize_pull_requests as $key => $value) {
            Storage::put($key.".txt", implode("\n", $value));
        }

        return [
            "states"=> true,
            "data" =>'',
            'message' =>"Files have been created successfully"
        ];

    }
    private function format_pull_request($pull_request){
        return "PR #{$pull_request['number']}: {$pull_request['title']} ({$pull_request['html_url']})";
    }

    private function is_review_successful($pull_request){
        try {
            $reviews = GitHub::pullRequest()->reviews()->all($this->owner, $this->repo, $pull_request['number']);

            foreach ($reviews as $review) {
                if ($review['state'] === 'APPROVED') {
                    return true;
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

}

