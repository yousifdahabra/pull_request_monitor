<?php

namespace App\Http\Controllers;

use Google_Client;

class GoogleController extends Controller{

    public function access_google_sheet(){

        $google_client = new Google_Client();
    }
}
