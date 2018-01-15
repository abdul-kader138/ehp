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

    public function addDeficiencyCategory($data)
    {
        if($this->db->insert('deficiency_category', $data)) return true;
        else return false;

    }


    public function getDeficiencyCategoryByName($name)
    {
        $q = $this->db->get_where('deficiency_category', array('category_name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }



    public function getDeficiencyCategoryByCode($name)
    {

        $q = $this->db->get_where('deficiency_category', array('category_code' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }


    public function getRQNextAI()
    {
        $this->db->select_max('id');
        $q = $this->db->get('deficiency_category');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "DC" . "-" . sprintf("%05s", $row->id + 1);
        }

        return FALSE;

    }

    public function updateDeficiencyCategory($name, $data = array())
    {
        $this->db->where('category_code', $name);
        if ($this->db->update("deficiency_category", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteDeficiencyCategory($name)
    {
        if ($this->db->delete("deficiency_category", array('category_code' => $name))) {
            return true;
        }
        return FALSE;
    }


    public function getRQNextAIForDetails()
    {
        $this->db->select_max('id');
        $q = $this->db->get('deficiency_details');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "DD" . "-" . sprintf("%05s", $row->id + 1);
        }

        return FALSE;

    }


    public function addDeficiencyDetails($data)
    {
        if($this->db->insert('deficiency_details', $data)) return true;
        else return false;

    }

    public function updateDeficiencyDetails($name, $data = array())
    {
        $this->db->where('details_code', $name);
        if ($this->db->update("deficiency_details", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteDeficiencyDetails($name)
    {
        if ($this->db->delete("deficiency_details", array('details_code' => $name))) {
            return true;
        }
        return FALSE;
    }


    public function getDeficiencyDetailsByName($name)
    {
        $q = $this->db->get_where('deficiency_details', array('details_name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getDeficiencyDetailsByCode($name)
    {
        $q = $this->db->get_where('deficiency_details', array('details_code' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllCategories()
    {
        $q = $this->db->get("deficiency_category");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }


    public function getDeficiencyConcernByName($name)
    {
        $q = $this->db->get_where('deficiency_concern', array('concern_name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }



    public function getDeficiencyConcernByCode($name)
    {

        $q = $this->db->get_where('deficiency_concern', array('concern_code' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }


    public function getRQNextAIForConcern()
    {
        $this->db->select_max('id');
        $q = $this->db->get('deficiency_concern');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "DC" . "-" . sprintf("%05s", $row->id + 1);
        }

        return FALSE;

    }


    public function addDeficiencyConcern($data)
    {
        if($this->db->insert('deficiency_concern', $data)) return true;
        else return false;

    }


    public function updateDeficiencyConcern($name, $data = array())
    {
        $this->db->where('concern_code', $name);
        if ($this->db->update("deficiency_concern", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteDeficiencyConcern($name)
    {
        if ($this->db->delete("deficiency_concern", array('concern_code' => $name))) {
            return true;
        }
        return FALSE;
    }


}