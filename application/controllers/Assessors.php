<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Assessors extends MY_Controller
{

	public function __construct()
	{
            parent::__construct();
            
            $this->load->model('users_model');
	
    }
    
    public function index()
    {
        
        $data['assessors'] = $this->users_model->getRecords('assessors');
        
		// Set page specific title
		$this->template->write('title', 'OneFile Data Monitor : Assessors', TRUE);

		$this->template->write_view('content', 'assessors', $data, TRUE);
		$this->template->render();
    }
    
}
