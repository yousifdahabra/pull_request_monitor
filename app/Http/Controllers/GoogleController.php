<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Sheets;
use Illuminate\Http\Request;

class GoogleController extends Controller{

    public function access_google_sheet(){

        $google_client = new Google_Client();

        $service_account_path = storage_path('app/client_secret.json');
        $google_client->setAuthConfig($service_account_path);

        $google_client->addScope('https://www.googleapis.com/auth/spreadsheets');

        $sheets_service = new Google_Service_Sheets($google_client);


        $spreadsheet_id = '1u1r1hF5Kp57yf3hg1IlKw33u4oEIuEjm88MTqW1XZe8';
        $range = 'Sheet1';

        $response = $sheets_service->spreadsheets_values->get($spreadsheet_id, $range);

        $values = $response->getValues();

        return response()->json($values);
    }
}
