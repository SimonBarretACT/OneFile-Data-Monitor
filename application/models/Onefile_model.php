<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Onefile_model extends CI_Model {

    var $ci;
    public $sessionKey;
    public $restclient;
    public $onefileUrl;
    public $organisationID;

    public function __construct()
    {
        parent::__construct();

        // Set onefile api credentials

        $this->ci =& get_instance();
        if (file_exists(APPPATH . 'config/onefile_local.php')):
            $this->ci->config->load('onefile_local');            
        else:
            $this->ci->config->load('onefile');
        endif;

        // organisationID
        $this->organisationID = $this->ci->config->item('onefile_organisation_id');

        //X-CustomerToken
        $customerToken  = $this->ci->config->item('onefile_customer_token');

        //Headers
        $headers = array('header' => array('X-CustomerToken' => $customerToken));
        
        //Load restclient
        $this->ci->load
            ->add_package_path(APPPATH . '../vendor/maltyxx/restclient')
            ->library('Restclient', $headers)
            ->remove_package_path(APPPATH . '../vendor/maltyxx/restclient');
        
        // Get the onefile url
        $this->onefileUrl = $this->ci->config->item('onefile_base_url');

        //Get the session key
        $this->sessionKey = $this->ci->restclient->post($this->onefileUrl . 'Authentication', json_encode([]));

        //Initialise headers with session key
        $headers = array('header' => array('X-TokenID' => $this->sessionKey));
        
        //Create instance
        $this->restclient = new restclient($headers);

    }

    public function getUserFromId($id)
    {
        return $this->restclient->get($this->onefileUrl . 'User/' . $id, []);
    }

    public function archiveUserFromId($id)
    {
        var_dump( $this->restclient);
        die();
        return $this->restclient->post($this->onefileUrl . 'User/' . $id . '/Archive', json_encode(array(
            'organisationID' => $this->organisationID
        )));

    }

    public function unarchiveUserFromId($id)
    {
        return $this->restclient->post($this->onefileUrl . 'User/' . $id . '/Unarchive', json_encode(array(
            'organisationID' => $this->organisationID
        )));

    }

    public function getUsers($role = 1, $page = 1, $perPage = 50)
    {
        return $this->restclient->post($this->onefileUrl . 'User/Search/' . $page .'/' . $perPage, ['role' => $role, 'organisationID' => 2167]);
    }

}