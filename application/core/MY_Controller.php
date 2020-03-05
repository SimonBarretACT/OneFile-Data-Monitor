<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of my_controller
 *
 * @author https://www.roytuts.com
 */
class MY_Controller extends CI_Controller {

    protected $currentUser;
    protected $data;

    function __construct() {
        parent::__construct();

        // Initialise Parse server
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
        
        $this->currentUser = Parse\ParseUser::getCurrentUser();

        //Redirect if user is not signed in
        if ((!$this->currentUser) and ($this->router->fetch_class() !='signin') and ($this->router->fetch_method() !='learner')):
            redirect('signin');
        endif;

        //Set user name
        if ($this->currentUser):
            $this->data['name'] = $this->currentUser->get("name");
            $this->data['avatar'] = $this->currentUser->get("avatar");
        else:
            $this->data['name'] = 'Unknown';
            $this->data['avatar'] = '';
        endif;

        //default title
        $this->template->write('title', 'OneFile Data Monitor', TRUE);

        //default meta description
        $this->template->add_meta('description', 'A method of monitoring OneFile data for data analysis.');
        
        //default meta keywords
        $this->template->add_meta('keywords', 'OneFile,ACT Training');

        //it is better to include header and footer here because these will be used by every page
        $this->template->write_view('header', 'templates/snippets/header', $this->data);
        $this->template->write_view('footer', 'templates/snippets/footer');
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
