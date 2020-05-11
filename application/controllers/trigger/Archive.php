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

		// Remove all whitelist accounts
		$userRecords = new MyIterator_Filter_Whitelist(
			$userRecords
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

		//Save the snapshot
		$this->snapshot->save($data);

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

	}

	/**
	 * Auto archive
	 *
	 */
	public function auto() {

		$count = 0;

		//Get the candidates for archiving
		$query = new Parse\ParseQuery("Archive");
		$query->descending("createdAt");
		$object = $query->first();

		$candidates = $object->get("records");

		foreach ($candidates as $candidate):

			if (!$this->whitelist->islocked($candidate['UserID'])):
				//Wait to avoid api limit
				usleep(750000);

				if ($this->archiver->archive($candidate['UserID'])):
					$count++;
				endif;
			endif;

		endforeach;

		$html = "<p>$count accounts have been archived.</p>";
		$plain = "$count accounts have been archived.";

		$this->sendmail->sendGrid('simonbarrett@acttraining.org.uk', 'Simon Barrett', 'Auto-Archive', $html, $plain);

		//Set data for snapshot
		$data['archiveCandidates'] 	= $count;

		//Save the snapshot
		$this->snapshot->save($data);

	}

	/**
	 * Request to archive
	 *
	 */
	public function requests() {

		$ratelimit = ratelimiter();

		$count = 0;

		//Get the candidates for archiving
		$query = new Parse\ParseQuery("Request");
		$candidates = $query->find();

		foreach ($candidates as $candidate):
			
			//Wait to avoid api limit
			$ratelimit();

			//getUserByName
			if($candidate->get('userId')):
				$found = $this->onefile->getUser($candidate->get('userId'));
			else:
				$found = $this->onefile->getUserByName($candidate->get('firstname'), $candidate->get('lastname'), $candidate->get('role'));
			endif;

			if ($found !== ""):
				$ratelimit();
				$user = json_decode($found, true);
				if ($this->archiver->archive($user[0]['ID'], false)):
					$count++;
					$candidate->destroy();
				endif;
			endif;

		endforeach;

		if ($count > 0):
			$html = "<p>$count accounts have been archived.</p>";
			$plain = "$count accounts have been archived.";

			$this->sendmail->sendGrid('simonbarrett@acttraining.org.uk', 'Simon Barrett', 'Archive Requests', $html, $plain);
		endif;

	}

}
