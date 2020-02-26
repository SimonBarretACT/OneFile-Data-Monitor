<?php
defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;

class Archive extends MY_Controller
{

    protected $parse_appid;
	protected $parse_masterkey;
	protected $parse_server;
	protected $parse_path;

	public function __construct()
	{
			parent::__construct();
			
			$this->parse_appid 		= $this->config->item('parse_appid');
			$this->parse_masterkey 	= $this->config->item('parse_masterkey');
			$this->parse_server 	= $this->config->item('parse_server');
			$this->parse_path 		= $this->config->item('parse_path');

			Parse\ParseClient::initialize($this->parse_appid, null, $this->parse_masterkey);
			Parse\ParseClient::setServerURL($this->parse_server, $this->parse_path);
			$health = Parse\ParseClient::getServerHealth();
			if ($health['status'] !== 200) {
				die('Oops! There seems to be something wrong.');
			}
	
    }

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{

		$query = new Parse\ParseQuery("Archive");
		$query->descending("createdAt");
		$object = $query->first();

		$data['candidates'] = $object->get("records");

		// sort on DateCreated
		uasort($data['candidates'], function ($one, $two) {
			
			$first = $one['DateCreated'];
			$second = $two['DateCreated'];

			if ($first === $second) {
				return 0;
			}
			return $first < $second ? -1 : 1;

		});

		$this->sendgrid->send(
								'simonbarrett@acttraining.org.uk', 
								'Simon Barrett', 
								'simon.barrett.act@gmail.com', 
								'Simon Barrett', 
								'Test Email', 
								'This is a <strong>test</strong> email.'
							);
		
		// Set page specific title
		$this->template->write('title', 'OneFile Data Monitor : Archive', TRUE);

		$this->template->write_view('content', 'archive', $data, TRUE);
		$this->template->render();
	}

	/**
	 * Archive a learner
	 *
	 */
	public function learner($id)
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$base_url = $this->config->item('onefile_base_url');
		$token = $this->config->item('onefile_customer_token');
		$organisationId = $this->config->item('onefile_organisation_id'); 

		$client = new Client([
			// Base URI is used with relative requests
			'base_uri' => $base_url
		]);

		$response = $client->request('POST', 'Authentication',
		['headers' => [
			'X-CustomerToken' => $token,
			'Content-Type' => 'application/x-www-form-urlencoded'
			]]);
		$sessionKey = $response->getBody();

		$response = $client->request('POST', "User/$id/Archive",
		[
			'headers' => [
			'X-TokenID' => strval($sessionKey),
			'Content-Type' => 'application/x-www-form-urlencoded'
			],
			'form_params' => [
				'role' => 1,
				'organisationID' => $organisationId
			]
		]);	
		
		//Get the candidates for updaing
		$query = new Parse\ParseQuery("Archive");
		$query->descending("createdAt");
		$object = $query->first();

		$candidates = $object->get("records");
		$archived = $object->get("archived");

		if (!$archived):
			$archived = [];
		endif;

		//Find the record to update
		$recordIndex = search_records($candidates, false, $id, 'UserID');

		//Remove record and save
		if ($recordIndex):
			//Add to the archived records
			$archived[] = $candidates[$recordIndex];
			unset($candidates[$recordIndex]);
			$object->setAssociativeArray("records", $candidates);
			$object->setAssociativeArray("archived", $archived);
			$object->save(true);
		endif;

	}

}
