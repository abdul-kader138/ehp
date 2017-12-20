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

    public function getBuildingDetailsById($id)
    {
        $q = $this->db->get_where('building_details', array('id' => $id), 1);
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

    public function updateBuildingDetails($id, $data = array())
    {
        if ($this->db->update("building_details", $data,array('id'=>$id))) {
            return true;
        } else {
            return false;
        }
    }


    public function deleteBuildingDetails($id)
    {
        if($this->db->delete("building_details", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getRQNextAI()
    {
        $this->db->select_max('id');
        $q = $this->db->get('building');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "B" . "-" . sprintf("%09s", $row->id + 1);
        }

        return FALSE;

    }

}

/* End of file calegories_model.php */
/* Location: ./sma/modules/categories/models/categories_model.php */
