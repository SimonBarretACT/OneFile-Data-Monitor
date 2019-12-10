<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Archive extends MY_Controller
{

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

		Parse\ParseClient::initialize($parse_appid, null, $parse_masterkey);
		Parse\ParseClient::setServerURL($parse_server, $parse_path);
		$health = Parse\ParseClient::getServerHealth();
		if ($health['status'] !== 200) {
			die('Oops! There seems to be something wrong.');
		}

		$query = new Parse\ParseQuery("Archive");
		$query->descending("createdAt");
		$object = $query->first();

		$data['candidates'] = $object->get("records");

		// sendMailgun([
		// 	'from'    => $this->config->item('mailgun_from'),
		// 	'to'      => 'Simon Barrett <simonbarrett@acttraining.org.uk>',
		// 	'subject' => 'The PHP SDK is awesome!',
		// 	'text'    => 'It is so simple to send a message.'
		// 	]);


		// Set page specific title
		$this->template->write('title', 'OneFile Data Monitor : Archive', TRUE);

		$this->template->write_view('content', 'archive', $data, TRUE);
		$this->template->render();
	}
}
