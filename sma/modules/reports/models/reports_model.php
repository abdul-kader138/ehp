<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
| -----------------------------------------------------
| PRODUCT NAME: 	SCHOOL MANAGER 
| -----------------------------------------------------
| AUTHER:			MIAN SALEEM 
| -----------------------------------------------------
| EMAIL:			saleem@tecdiary.com 
| -----------------------------------------------------
| COPYRIGHTS:		RESERVED BY TECDIARY IT SOLUTIONS
| -----------------------------------------------------
| WEBSITE:			http://tecdiary.net
| -----------------------------------------------------
|
| MODULE: 			Reports
| -----------------------------------------------------
| This is reports module model file.
| -----------------------------------------------------
*/


class Reports_model extends CI_Model
{
	
	
	public function __construct()
	{
		parent::__construct();

	}
	


    public function getAllBuildings()
    {
        $q = $this->db->get_where('building',array('isTaggedWithVendor'=>'Yes'));
        if($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }


    public function getAllBuildingForInvoice()
    {
        $q = $this->db->get("building");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }
}
