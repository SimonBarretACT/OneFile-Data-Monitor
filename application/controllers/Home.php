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

		$query = new Parse\ParseQuery("Snapshot");
		$query->descending("createdAt");
		$query->limit(2); // limit to at most 2 results
		$objects = $query->find();
		
		//set data for view
		$object = $objects[0]; //First one
		$data['allUsers'] 			= $object->get("allUsers");
		$data['activeUsers'] 		= $object->get("activeUsers");
		$data['last7Days'] 			= $object->get("last7Days");
		$data['archiveCandidates'] 	= $object->get("archiveCandidates");

		// Get yesterday
		if (count($objects) > 1):
			$yesterday = $objects[1]; //Second one
			$data['allUsersYesterday'] 			= $yesterday->get("allUsers");
			$data['activeUsersYesterday'] 		= $yesterday->get("activeUsers");
			$data['last7DaysYesterday'] 		= $yesterday->get("last7Days");
			$data['archiveCandidatesYesterday'] = $yesterday->get("archiveCandidates");
		else:
			$data['allUsersYesterday'] 			= 0;
			$data['activeUsersYesterday'] 		= 0;
			$data['last7DaysYesterday'] 		= 0;
			$data['archiveCandidatesYesterday'] = 0;
		endif;


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
