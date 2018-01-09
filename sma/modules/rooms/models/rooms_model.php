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



    public function addRoom($data)
    {
//        var_dump($data);
//
        if($this->db->insert("rooms", $data)) {
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



        if($this->db->update("rooms", $data,array('room_name'=>$name))) {
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
            return "Apt" . "-" . sprintf("%09s", $row->id + 1);
        }

        return FALSE;

    }



}

/* End of file calegories_model.php */
/* Location: ./sma/modules/categories/models/categories_model.php */
