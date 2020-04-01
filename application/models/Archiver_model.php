<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class Archiver_model extends CI_Model {

protected $parse_appid;
protected $parse_masterkey;
protected $parse_server;
protected $parse_path;

protected $base_url;
protected $token;
protected $organisationId; 

protected $client;
protected $sessionKey;


public function __construct()
	{
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
    
            $this->base_url = $this->config->item('onefile_base_url');
            $this->token = $this->config->item('onefile_customer_token');
            $this->organisationId = $this->config->item('onefile_organisation_id'); 
    
            $this->client = new Client([
                // Base URI is used with relative requests
                'base_uri' => $this->base_url
            ]);

            $response = $this->client->request('POST', 'Authentication',
            ['headers' => [
                'X-CustomerToken' => $this->token,
                'Content-Type' => 'application/x-www-form-urlencoded'
                ]]);
            $this->sessionKey = $response->getBody();

    }

    public function archive($id, $sendEmail=true)
    {

		try {
		$this->client->request('POST', "User/$id/Archive",
		[
			'headers' => [
			'X-TokenID' => strval($this->sessionKey),
			'Content-Type' => 'application/x-www-form-urlencoded'
			],
			'form_params' => [
				'role' => 1,
				'organisationID' => $this->organisationId
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
		
		if ($recordIndex >= 0):

		$assessorName = $candidates[$recordIndex]['DefaultAssessor'];
		$learner = $candidates[$recordIndex]['FirstName'] . ' ' . $candidates[$recordIndex]['LastName'];

		$found = $this->onefile->getUserByName($assessorName, 5);

		$records = json_decode($found, true);
		$assessorEmail = '';
		$assessorId = 0;

		if (is_array($records) and count($records) > 0):
			$assessorId = $records[0]['ID'];
			$assessor = json_decode($this->onefile->getUser($assessorId), true);
			$assessorEmail = $assessor['Email'];
		endif;

		if ($assessorEmail and $sendEmail):
			$this->sendmail->sendGridDynamic(
											$assessorEmail, 
											$assessorName, 
											"$learner's OneFile Account has been archived", 
											$learner,
											$id);
			$this->message->save($assessorName, $assessorEmail, $id, $assessorId);
		endif;

		//Remove record and save
		//Add to the archived records
		$archived[] = $candidates[$recordIndex];
		unset($candidates[$recordIndex]);
		$object->setAssociativeArray("records", $candidates);
		$object->setAssociativeArray("archived", $archived);
		$object->save(true);

        endif;

        return true;
        
        } catch (Exception $e) {
            return false;
        }
    }

    public function unarchive($id)
    {

		try {
			$this->client->request('POST', "User/$id/Unarchive",
			[
				'headers' => [
				'X-TokenID' => strval($this->sessionKey),
				'Content-Type' => 'application/x-www-form-urlencoded'
				],
				'form_params' => [
					'role' => 1,
					'organisationID' => $this->organisationId
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

			return true;
		
		} catch (Exception $e) {
			return false;
		}

    }

 
}