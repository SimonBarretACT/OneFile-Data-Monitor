<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
class Datawaves_model extends CI_Model {

	protected $client;
	protected $project;
	protected $secret;

	public function __construct()
	{
		parent::__construct();

		//Create Guzzle Client
		$this->client = new Client([
			// Base URI is used with relative requests
			'base_uri' => 'https://datawaves.io/api/v1.0/'
		]);
		
		$this->secret = $this->config->item('datawaves_secret');
		$this->project = $this->config->item('datawaves_project');
	}


	public function recordEvent($collection, $params=[])
	{

		//Record event
		$response = $this->client->request('POST', 'projects/' . $this->config->item('datawaves_project') . '/events/' . $collection,
		['headers' => [
			'Authorization' => $this->config->item('datawaves_secret'),
			'Content-Type' => 'application/x-www-form-urlencoded'
		],
		'json' => json_encode($params)
		]);
		
		return $response->getBody();
	}


}
	