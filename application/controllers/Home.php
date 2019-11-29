<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{

		Parse\ParseClient::initialize( 'cavc-onefile-parse', null, '4{s$P(]B=rr2R+nDTLAK-,R4v]4c<4)F' );
		Parse\ParseClient::setServerURL('https://cavc-onefile-parse.herokuapp.com','parse');
		$health = Parse\ParseClient::getServerHealth();
		if($health['status'] !== 200) {
			die('Oops! There seems to be something wong.');
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
