<?php
defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;

class Unarchive extends MY_Controller
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

		$data['candidates'] = $object->get("archived");

		if ($data['candidates']):
		// sort on DateCreated
		uasort($data['candidates'], function ($one, $two) {
			
			$first = $one['DateCreated'];
			$second = $two['DateCreated'];

			if ($first === $second) {
				return 0;
			}
			return $first < $second ? -1 : 1;

		});
	endif;


		// Set page specific title
		$this->template->write('title', 'OneFile Data Monitor : Unarchive', TRUE);

		$this->template->write_view('content', 'unarchive', $data, TRUE);
		$this->template->render();
	}

	/**
	 * Unarchive a learner
	 *
	 */
	public function learner($id)
	{

		$base_url 		= $this->config->item('onefile_base_url');
		$token 			= $this->config->item('onefile_customer_token');
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


		try {
			$response = $client->request('POST', "User/$id/Unarchive",
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
			$recordIndex = search_records($archived, false, $id, 'UserID');
	
			//Remove record and save
			if ($recordIndex >= 0):
				//Add to the candidates records
				$candidates[] = $archived[$recordIndex];
				unset($archived[$recordIndex]);
				$object->setAssociativeArray("records", $candidates);
				$object->setAssociativeArray("archived", $archived);
				$object->save(true);
			endif;

			$error = false;
		
		} catch (Exception $e) {
			$error = true;
			echo 'Unarchive failed ', "\n";
		}
		

		if (!$this->input->is_ajax_request()) {
			$this->template->set_template('fullscreen');
			// Set page specific title
			$this->template->write('title', 'OneFile Data Monitor : Unarchived', TRUE);
			if ($error):
				$this->template->write_view('content', 'unarchived', [], TRUE);
			else:
				$this->template->write_view('content', 'unarchived-failed', [], TRUE);
			endif;
			$this->template->render();			
		}

	}

	/**
	 * Unarchive a list of learners and assign them to a group
	 *
	 */
	public function list($classroom=57911, $filename='unarchive.csv')
	{

		// Set file properties
		$trainees = 'unarchive.csv';
		$local_path = APPPATH . '/import/';

		// Fetch Unarchive records 
		$iteratorRecords = $this->csv->getRecords($local_path . $trainees);
		$Unarchive = iterator_to_array($iteratorRecords, true);

		$base_url 		= $this->config->item('onefile_base_url');
		$token 			= $this->config->item('onefile_customer_token');
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

		$count = 0;
		
		foreach ($Unarchive as $record):

				$id = $record['ID'];

				try {
					$response = $client->request('POST', "User/$id/Unarchive",
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

					$response = $client->request('POST', "User/$id",
					[
						'headers' => [
						'X-TokenID' => strval($sessionKey),
						'Content-Type' => 'application/x-www-form-urlencoded'
						],
						'form_params' => [
							'role' => 1,
							'organisationID' => $organisationId,
							'ClassroomID' => $classroom
						]
					]);

					$count++;

				} catch (Exception $e) {
					echo 'Unarchive failed for: ', $id,  "\n";
				}


			

		endforeach;

		echo "Completed: $count learners have been unarchived.";
		
	}


}
