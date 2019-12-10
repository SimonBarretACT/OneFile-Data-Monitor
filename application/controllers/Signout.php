<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Signout extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		Parse\ParseUser::logOut();
		redirect('signin');

	}


}
