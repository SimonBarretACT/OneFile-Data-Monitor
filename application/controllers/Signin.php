<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Signin extends MY_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->template->set_template('fullscreen');

		//default title
		$this->template->write('title', 'OneFile Data Monitor', TRUE);

		//default meta description
		$this->template->add_meta('description', 'A method of monitoring OneFile data for data analysis.');
		
		//default meta keywords
		$this->template->add_meta('keywords', 'OneFile,ACT Training');

	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->template->write_view('content', 'signin', null, TRUE);
			$this->template->render();
		}
		else
		{
			try {
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				$user = Parse\ParseUser::logIn($username, $password);
				// Do stuff after successful login.
				redirect('home');
			} catch (Parse\ParseException $error) {
				// The login failed. Check error to see why.
				$this->template->write_view('content', 'signin', null, TRUE);
				$this->template->render();
			}
		}

	}

	/**
	 * Password reset
	 *
	 */
	public function reset()
	{
		$this->template->write_view('content', 'reset', null, TRUE);
		$this->template->render();
	}

}
