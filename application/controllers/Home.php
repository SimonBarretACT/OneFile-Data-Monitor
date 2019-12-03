<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{

		$parse_appid 		= $this->config->item('parse_appid');
		$parse_masterkey 	= $this->config->item('parse_masterkey');
		$parse_server 		= $this->config->item('parse_server');
		$parse_path 		= $this->config->item('parse_path');

		Parse\ParseClient::initialize( $parse_appid, null, $parse_masterkey );
		Parse\ParseClient::setServerURL($parse_server, $parse_path);
		$health = Parse\ParseClient::getServerHealth();
		if($health['status'] !== 200) {
			die('Oops! There seems to be something wrong.');
		}

		$query = new Parse\ParseQuery("Snapshot");
		$query->descending("createdAt");
		$object = $query->first();


		//set data for view
		$data['allUsers'] 			= $object->get("allUsers");
		$data['activeUsers'] 		= $object->get("activeUsers");
		$data['last7Days'] 			= $object->get("last7Days");
		$data['archiveCandidates'] 	= $object->get("archiveCandidates");

		
		$this->load->view('home', $data);
	}
}
