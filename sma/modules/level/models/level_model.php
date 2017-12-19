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


class Level_model extends CI_Model
{


    public function __construct()
    {
        parent::__construct();

    }



    public function addLevel($name, $code)
    {

        if($this->db->insert("level", array('level_code' => $code, 'level_name' => $name,'created_by'=>USER_NAME,'created_date'=>date('Y-m-d H:i:s')))) {
            return true;
        } else {
            return false;
        }
    }



    public function getLevelByName($name)
    {
        $q = $this->db->get_where('level', array('level_name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }




    public function updateLevel($name, $data = array())
    {


        // Shelf data
        $levelData = array(
            'level_code'=> $data['code'],
            'level_name'=> $data['name'],
            'updated_by'=>USER_NAME,
            'updated_date'=>date('Y-m-d H:i:s')
        );
        $this->db->where('level_name', $name);
        if($this->db->update("level", $levelData)) {
            return true;
        } else {
            return false;
        }
    }




    public function deleteLevel($name)
    {
        if($this->db->delete("level", array('level_name' => $name))) {
            return true;
        }
        return FALSE;
    }



}

/* End of file calegories_model.php */
/* Location: ./sma/modules/categories/models/categories_model.php */
