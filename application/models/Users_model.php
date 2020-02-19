<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'third_party/Filters.php';

class Users_model extends CI_Model {

protected $filename;
protected $local_path;
protected $userRecords;


public function __construct()
{
        parent::__construct();
        
		// Set file properties
		$this->filename = 'alluser.csv';
        $this->local_path = APPPATH . '/tmp/';
        
		// Download to local using ftps connection
        $this->ftps->connection->download($this->filename, $this->local_path);
        
		// Remove all archived accounts
		$this->userRecords = new MyIterator_Filter_Archived(
			$this->csv->getRecords($this->local_path . $this->filename)
        );
        
}


public function getRecords($type='', $asArray = true)
{
    //Get all the records
    $records = $this->userRecords;

    switch ($type) {
        case "assessors":
            $records = new MyIterator_Filter_Assessor(
                $records
            );
            break;
        case "learners":
            $records = new MyIterator_Filter_Learner(
                $records
            );           
            break;
        default:
            //No need to do anything
    }

    if ($asArray):
        return iterator_to_array($records);
    else:
        return $records;
    endif;
}



}