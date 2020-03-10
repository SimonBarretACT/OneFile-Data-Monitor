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
				usleep(750000);

				$assessor = $candidate['DefaultAssessor'];
				$learner = $candidate['FirstName'] . ' ' . $candidate['LastName'];
		
				$found = $this->onefile->getUserFromId($assessor);
		
				if (is_array($found) and count($found) > 0):
					$assessorEmail = $found['Email'];
				else:
					$assessorEmail = '';
				endif;
		
				if ($assessorEmail):
					$this->sendmail->sendGridDynamic(
													$assessorEmail, 
													$assessor, 
													"$learner's OneFile Account has been archived", 
													$learner,
													$id);
				endif;
		

			endif;

		endforeach;

	}

}
