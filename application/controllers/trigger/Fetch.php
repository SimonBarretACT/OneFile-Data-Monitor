<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'third_party/Filters.php';

class Fetch extends CI_Controller {

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

		// Set file properties
		$alluser = 'alluser.csv';
		$local_path = APPPATH . '/tmp/';

		// Download to local using ftps connection
		$this->ftps->connection->download($alluser, $local_path);

		// Remove all archived accounts from alluser.csv
		$alluserRecords = new MyIterator_Filter_Archived(
			$this->csv->getRecords($local_path . $alluser)
		);

		//Find all users who have logged in
		$loggedInRecords = new MyIterator_Filter_LoggedIn(
			$alluserRecords
		);

		//Find all users who have logged in
		$last7DaysRecords = new MyIterator_Filter_LastWeek(
			$loggedInRecords
		);

		//set data for output
		$data['allUsers'] 			= iterator_count ($alluserRecords);
		$data['activeUsers'] 		= iterator_count ($loggedInRecords);
		$data['last7Days']			= iterator_count ($last7DaysRecords);
		$data['dayNumber']			= (int) date('z');
		$data['weekNumber']			= (int) date('W');
		$data['yearNumber']			= (int) date('Y');

		$snapshot = new Parse\ParseObject("Snapshot");

		$snapshot->set("allUsers", 				$data['allUsers']);
		$snapshot->set("activeUsers", 			$data['activeUsers']);
		$snapshot->set("last7Days", 			$data['last7Days']);
		$snapshot->set("dayNumber", 			$data['dayNumber']);
		$snapshot->set("weekNumber", 			$data['weekNumber']);
		$snapshot->set("yearNumber", 			$data['yearNumber']);
		
		try {
		  $snapshot->save();
		} catch (Parse\ParseException $ex) {  
		  // Execute any logic that should take place if the save fails.
		  // error is a ParseException object with an error code and message.
		  echo 'Failed to create new object, with error message: ' . $ex->getMessage();
		}

		echo 'Success: The data was retrieved and stored.';
	}
}
