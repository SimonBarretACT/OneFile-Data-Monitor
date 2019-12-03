<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller
{
    public function index()
    {
        //var_dump($this->onefile->getUserFromId(882031));
       var_dump($this->onefile->getUsers());
    }
}
