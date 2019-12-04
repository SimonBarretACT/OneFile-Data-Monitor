<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archive extends CI_Controller {

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

		$query = new Parse\ParseQuery("Archive");
		$query->descending("createdAt");
		$object = $query->first();

		$data['candidates'] = $object->get("records");

		$this->load->view('archive', $data);
	}
}
