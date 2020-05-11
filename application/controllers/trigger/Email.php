<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'third_party/Filters.php';

class Email extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		echo "Nothing here!";

	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function batch($id="")
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

		//Get the last set of archived accounts
		$query = new Parse\ParseQuery("Archive");
		if ($id != ""):
			$object = $query->get($id);
		else:
			$query->descending("createdAt");
			$object = $query->first();
		endif;

		$candidates = $object->get("archived");

		foreach ($candidates as $candidate):

			if (!$this->whitelist->islocked($candidate['UserID'])):
				//Wait to avoid api limit
				usleep(1500000);

				$assessorName = $candidate['DefaultAssessor'];

				$learner = $candidate['FirstName'] . ' ' . $candidate['LastName'];
		
				$found = $this->onefile->getUserByName($assessorName, '', 5);

				$records = json_decode($found, true);
				$assessorEmail = '';
				$assessorId = 0;

				if (is_array($records) and count($records) > 0):
					$assessorId = $records[0]['ID'];
					$assessor = json_decode($this->onefile->getUser($assessorId), true);
					$assessorEmail = $assessor['Email'];
				endif;


				echo "Send email to $assessorEmail".PHP_EOL;

				if ($assessorEmail):
					$this->sendmail->sendGridDynamic(
													$assessorEmail, 
													$assessorName, 
													"$learner's OneFile Account has been archived", 
													$learner,
													$id);
					$this->message->save($assessorName, $assessorEmail, $candidate['UserID'], $assessorId);
				endif;

			endif;

		endforeach;

	}

}
