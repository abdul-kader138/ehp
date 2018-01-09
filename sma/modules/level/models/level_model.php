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



    public function addLevel($data)
    {

        if($this->db->insert_batch("level",$data)) {
            return true;
        }
            return false;

    }



    public function getLevelByName($name,$room_name)
    {
        $q = $this->db->get_where('level', array('level_name' => $name,'room_code' => $room_name), 1);
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
            'room_name'=> $data['room_name'],
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




    public function deleteLevel($name,$room_name)
    {
        if($this->db->delete("level", array('level_code' => $name,'room_code'=>$room_name))) {
            return true;
        }
        return FALSE;
    }



    public function getRQNextAI()
    {
        $this->db->select_max('id');
        $q = $this->db->get('level');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "LV" . "-" . sprintf("%09s", $row->id + 1);
        }

        return FALSE;

    }

    public function getAllRooms()
    {
        $q = $this->db->get("rooms");
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
