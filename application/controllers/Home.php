<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct() {
        parent::__construct();
	}
	
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

		// To set page specific title
		// $this->template->write('title', 'OneFile Data Monitor', TRUE);
		
        // 
        //  if you have any js to add for this page
        //  
        //$this->template->add_js('assets/js/niceforms.js');
        //
        // * if you have any css to add for this page
        //
	    // $this->template->add_css('assets/css/page.css');
	   
        $this->template->write_view('content', 'home', $data, TRUE);
		$this->template->render();
			
	}
}
