<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'third_party/Filters.php';

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

		// Set file properties
		$user = 'user.csv';
		$local_path = APPPATH . '/tmp/';

		// Download to local using ftps connection
		$this->ftps->connection->download($user, $local_path);

		// Remove all archived accounts from user.csv
		$userRecords = new MyIterator_Filter_Archived(
			$this->csv->getRecords($local_path . $user)
		);

		//Find archive candidates
		$archiveRecords = new MyIterator_Filter_Archive(
			$userRecords
		);

		//set data for snapshot
		$data['archiveCandidates'] 	= iterator_count ($archiveRecords);
		$data['dayNumber']			= (int) date('z');
		$data['weekNumber']			= (int) date('W');
		$data['yearNumber']			= (int) date('Y');

		//Check if there is already a snapshot for today
		$query = new Parse\ParseQuery("Snapshot");
		$query->equalTo("dayNumber", $data['dayNumber']);
		$query->equalTo("weekNumber", $data['weekNumber']);
		$query->equalTo("yearNumber", $data['yearNumber']);
		$snapshot = $query->first();

		if (!$snapshot):
			$snapshot = new Parse\ParseObject("Snapshot");
			$snapshot->set("dayNumber", 		$data['dayNumber']);
			$snapshot->set("weekNumber", 		$data['weekNumber']);
			$snapshot->set("yearNumber", 		$data['yearNumber']);
		endif;

		$snapshot->set("archiveCandidates", $data['archiveCandidates']);

		try {
		  $snapshot->save(true);
		} catch (Parse\ParseException $ex) {  
		  // Execute any logic that should take place if the save fails.
		  // error is a ParseException object with an error code and message.
		  echo 'Failed to create new object, with error message: ' . $ex->getMessage();
		}

		$archive = new Parse\ParseObject("Archive");
		$archive->setAssociativeArray("records", iterator_to_array($archiveRecords, false));
		$archive->set("archiveCandidates", iterator_count ($archiveRecords));
		$archive->set("is_cli", is_cli());

		try {
			$archive->save(true);
		  } catch (Parse\ParseException $ex) {  
			// Execute any logic that should take place if the save fails.
			// error is a ParseException object with an error code and message.
			echo 'Failed to create new object, with error message: ' . $ex->getMessage();
		  }

		echo 'Success: The data was retrieved and stored.';

		$config['fromEmail'] 	= "simonbarrett@acttraining.org.uk";
		$config['fromName']  	= "ACT Training";
		$config['subject']  	= "Sending with SendGrid is Fun";
		$config['toEmail']  	= "simonbarrett@me.com";
		$config['toName']  		= "Simon Barrett";
		$config['contentPlain'] = "and easy to do anywhere, even with PHP";
		$config['contentHtml']  = "<strong>and easy to do anywhere, even with PHP</strong>";

		sendGrid($config, true);
		
	}
}
