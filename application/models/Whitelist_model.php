<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Whitelist_model extends CI_Model {

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

    public function account($id)
    {
		
		$object = new Parse\ParseObject("Whitelist");

		
		//Using PHP's DateTime object to add 30 days
		//to a given date.
		$date = new DateTime();
		
		//Create a new DateInterval object using P30D.
		$interval = new DateInterval('P30D');
		
		//Add the DateInterval object to our DateTime object.
		$date->add($interval);

		$object->set("id", $id);
		$object->set("untilDate", $date);
		
		try {
		  $object->save();
		} catch (Parse\ParseException $ex) {  
		  // Execute any logic that should take place if the save fails.
		  // error is a ParseException object with an error code and message.
		  echo 'Failed to create new object, with error message: ' . $ex->getMessage();
		}
    }


}