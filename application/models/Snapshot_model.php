<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Snapshot_model extends CI_Model {

protected $parse_appid;
protected $parse_masterkey;
protected $parse_server;
protected $parse_path;


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

    }

    public function save($data)
    {
		
		if (!isset($data['dayNumber'])):
			$data['dayNumber']			= (int) date('z');
		endif;
		if (!isset($data['weekNumber'])):
			$data['weekNumber']			= (int) date('W');
		endif;
		if (!isset($data['yearNumber'])):
			$data['yearNumber']			= (int) date('Y');
		endif;

		//Check if there is already a snapshot for today
		$query = new Parse\ParseQuery("Snapshot");
		$query->equalTo("dayNumber", $data['dayNumber']);
		$query->equalTo("weekNumber", $data['weekNumber']);
		$query->equalTo("yearNumber", $data['yearNumber']);
		$snapshot = $query->first();

		if (!$snapshot):
			$snapshot = new Parse\ParseObject("Snapshot");
		endif;

		foreach ($data as $key => $value):
			$snapshot->set($key, $value);
		endforeach;

		try {
		  $snapshot->save();
		} catch (Parse\ParseException $ex) {  
		  // Execute any logic that should take place if the save fails.
		  // error is a ParseException object with an error code and message.
		  echo 'Failed to create new object, with error message: ' . $ex->getMessage();
		  return false;
		}

		return true;
    }

    public function islocked($id)
    {
		$query = new Parse\ParseQuery("Whitelist");
		$query->equalTo("oneFileId", intval($id));
		$results = $query->find();

		return ($results and (count($results) > 0));

	}
}