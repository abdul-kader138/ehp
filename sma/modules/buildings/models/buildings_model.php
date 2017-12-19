<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


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
    | MODULE: 			level
    | -----------------------------------------------------
    | This is level module model file.
    | -----------------------------------------------------
    */


class Buildings_model extends CI_Model
{


    public function __construct()
    {
        parent::__construct();

    }



    public function addBuilding($data)
    {
        if($this->db->insert("building", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function addBuildingDetails($data)
    {
        if($this->db->insert("building_details", $data)) {
            return true;
        } else {
            return false;
        }
    }


    public function getBuildingsByName($name)
    {
        $q = $this->db->get_where('building', array('building_name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }




    public function updateBuildings($name, $data = array())
    {
            if ($this->db->update("building", $data,array('building_name'=>$name))) {
                return true;
            } else {
                return false;
            }
    }




    public function deleteBuildings($name)
    {
        if($this->db->delete("building", array('building_name' => $name))) {
            return true;
        }
        return FALSE;
    }


    public function getAllBuildings()
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

    public function getAllLevels()
    {
        $q = $this->db->get("level");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }
}

/* End of file calegories_model.php */
/* Location: ./sma/modules/categories/models/categories_model.php */
