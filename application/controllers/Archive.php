<?php
defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;

class Archive extends MY_Controller
{

    protected $parse_appid;
	protected $parse_masterkey;
	protected $parse_server;
	protected $parse_path;

	public function __construct()
	{
			parent::__construct();
			
			$this->parse_appid 		= $this->config->item('parse_appid');
			$this->parse_masterkey 	= $this->config->item('parse_masterkey');
			$this->parse_server 	= $this->config->item('parse_server');
			$this->parse_path 		= $this->config->item('parse_path');

			Parse\ParseClient::initialize($this->parse_appid, null, $this->parse_masterkey);
			Parse\ParseClient::setServerURL($this->parse_server, $this->parse_path);
			$health = Parse\ParseClient::getServerHealth();
			if ($health['status'] !== 200) {
				die('Oops! There seems to be something wrong.');
			}
	
    }

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{

		$query = new Parse\ParseQuery("Archive");
		$query->descending("createdAt");
		$object = $query->first();

		$this->data['candidates'] = $object->get("records");

		// sort on DateCreated
		uasort($this->data['candidates'], function ($one, $two) {
			
			$first = $one['DateCreated'];
			$second = $two['DateCreated'];

			if ($first === $second) {
				return 0;
			}
			return $first < $second ? -1 : 1;

		});
		
		// Set page specific title
		$this->template->write('title', 'OneFile Data Monitor : Archive', TRUE);

		$this->template->write_view('content', 'archive', $this->data, TRUE);
		$this->template->render();
	}

	/**
	 * Archive a learner
	 *
	 */
	public function learner($id)
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$this->archiver->archive($id);
	
	}

}
