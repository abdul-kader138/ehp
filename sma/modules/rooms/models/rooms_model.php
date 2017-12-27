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


class Rooms_model extends CI_Model
{


    public function __construct()
    {
        parent::__construct();

    }



    public function addRoom($name, $code,$total_bed_qty)
    {

        if($this->db->insert("rooms", array('room_code' => $code, 'room_name' => $name,'total_bed_qty'=>$total_bed_qty,'created_by'=>USER_NAME,'created_date'=>date('Y-m-d H:i:s')))) {
            return true;
        } else {
            return false;
        }
    }



    public function getRoomByName($name)
    {
        $q = $this->db->get_where('rooms', array('room_name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }




    public function updateRoom($name, $data = array())
    {


        // Shelf data
        $levelData = array(
            'room_code'=> $data['code'],
            'room_name'=> $data['name'],
            'total_bed_qty'=> $data['total_bed_qty'],
            'updated_by'=>USER_NAME,
            'updated_date'=>date('Y-m-d H:i:s')
        );
        $this->db->where('room_name', $name);
        if($this->db->update("rooms", $levelData)) {
            return true;
        } else {
            return false;
        }
    }




    public function deleteLevel($name)
    {
        if($this->db->delete("rooms", array('room_name' => $name))) {
            return true;
        }
        return FALSE;
    }



    public function getRQNextAI()
    {
        $this->db->select_max('id');
        $q = $this->db->get('rooms');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "RM" . "-" . sprintf("%09s", $row->id + 1);
        }

        return FALSE;

    }



}

/* End of file calegories_model.php */
/* Location: ./sma/modules/categories/models/categories_model.php */
