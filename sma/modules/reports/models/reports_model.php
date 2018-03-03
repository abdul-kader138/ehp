<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


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
        $q = $this->db->get_where('building', array('isTaggedWithVendor' => 'Yes'));
        if ($q->num_rows() > 0) {
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

    function getAllInvoiceItemsWithDetails($sDate,$eDate,$code)
    {

        $qry = "select a.building_code,a.building_name,a.location, a.room_name,a.room_rent,client_intake.move_in_date,client_intake.move_out_date,concat(clients.first_name,' ',clients.last_name) as name,clients.ssn from (SELECT building.building_name,building.building_code,building.location,rooms.room_code, rooms.room_name,rooms.room_rent FROM `building` inner join building_details on building.building_code=building_details.building_code INNER join level on building_details.level_code=level.level_code inner join rooms on level.room_code=rooms.room_code where building.building_code='" . $code . "' GROUP by rooms.room_code order by rooms.room_name ASC ) as a left join client_intake on a.building_code=client_intake.building_code and a.room_code=client_intake.apartment_code and client_intake.move_in_date BETWEEN '" . $sDate . "' and '" . $eDate . "' left join clients on client_intake.client_code=clients.code";
        $q = $this->db->query($qry);
        if($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

    }
}
