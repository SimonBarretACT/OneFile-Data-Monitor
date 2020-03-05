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
		$this->data['allUsers'] 			= $object->get("allUsers");
		$this->data['activeUsers'] 			= $object->get("activeUsers");
		$this->data['assessors'] 			= $object->get("assessors");
		$this->data['last7Days'] 			= $object->get("last7Days");
		$this->data['lastMonth'] 			= $object->get("lastMonth");
		$this->data['archiveCandidates'] 	= $object->get("archiveCandidates");

		// Get yesterday
		if (count($objects) > 1):
			$yesterday = $objects[1]; //Second one
			$this->data['allUsersYesterday'] 			= $yesterday->get("allUsers");
			$this->data['activeUsersYesterday'] 		= $yesterday->get("activeUsers");
			$this->data['assessorsYesterday'] 			= $yesterday->get("assessors");
			$this->data['last7DaysYesterday'] 			= $yesterday->get("last7Days");
			$this->data['lastMonthYesterday'] 			= $yesterday->get("lastMonth");
			$this->data['archiveCandidatesYesterday'] 	= $yesterday->get("archiveCandidates");
		else:
			$this->data['allUsersYesterday'] 			= 0;
			$this->data['activeUsersYesterday'] 		= 0;
			$this->data['assessorsYesterday'] 		= 0;
			$this->data['last7DaysYesterday'] 		= 0;
			$this->data['lastMonthYesterday'] 		= 0;
			$this->data['archiveCandidatesYesterday'] = 0;
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
	   
        $this->template->write_view('content', 'home', $this->data, TRUE);
		$this->template->render();
			
	}
}
