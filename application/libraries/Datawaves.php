<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class Datawaves {


    public function __construct()
    {
        parent::__construct();

        //Create Guzzle Client
		$this->client = new Client([
			// Base URI is used with relative requests
			'base_uri' => 'https://datawaves.io/api/v1.0'
        ]);
        
		//Authenticate
		$response = $this->client->request('POST', 'Authentication',
		['headers' => [
			'Authorization' => 'e2b038a7a816ft1$sHegwn8vbu0fEWaQ4f80afdb7e5384885460',
			'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);
		$this->sessionKey = $response->getBody();

    }


}
