<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


/*
    | -----------------------------------------------------
    | PRODUCT NAME: 	EHP
    | -----------------------------------------------------
    | AUTHER:			Abdul Kader
    | -----------------------------------------------------
    | EMAIL:			babu313136@gmail.com
    | -----------------------------------------------------
    | COPYRIGHTS:		RESERVED BY One Click SOLUTIONS
    | -----------------------------------------------------
    | WEBSITE:
    | -----------------------------------------------------
    |
    | MODULE: 			inspection
    | -----------------------------------------------------
    | This is level module model file.
    | -----------------------------------------------------
    */


class Inspection_model extends CI_Model
{


    public function __construct()
    {
        parent::__construct();

    }

    public function addInspection($data)
    {
        if ($this->db->insert_batch("inspection", $data)) return true;
        else return false;

    }


    public function getInspectionByName($name)
    {
        $q = $this->db->get_where('inspection', array('category_name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }


    public function getRQNextAI()
    {
        $this->db->select_max('id');
        $q = $this->db->get('inspection');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "IC" . "-" . sprintf("%05s", $row->id + 1);
        }

        return FALSE;

    }


}