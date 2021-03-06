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
    | MODULE: 			Report
| -----------------------------------------------------
    | This is level module model file.
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

    function getValidInvoiceItemsWithDetails($sDate,$eDate,$code)
    {

        $qry = "select a.building_code,a.building_name,a.location,sum(a.room_rent) as rents,customers.name as c_name
               from (SELECT building.building_name,building.building_code,building.location,rooms.room_code,
                rooms.room_name,rooms.room_rent FROM `building` inner join building_details on
                building.building_code=building_details.building_code INNER join level on
                building_details.level_code=level.level_code inner join rooms on
                level.room_code=rooms.room_code where building.building_code='" . $code . "'
                GROUP by rooms.room_code order by rooms.room_name ASC ) as a inner join
                client_intake on a.building_code=client_intake.building_code and
                 a.room_code=client_intake.apartment_code and client_intake.move_in_date
                 BETWEEN '" . $sDate . "' and '" . $eDate . "'
                 and client_intake.move_in_date is not null INNER join
                 customers on client_intake.vendor_code=customers.code";
        $q = $this->db->query($qry);
        if($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

    }

    public function getRQNextInvoice()
    {
        $this->db->select_max('id');
        $q = $this->db->get('invoice');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "INV" . "-" . sprintf("%05s", $row->id + 1);
        }

        return FALSE;

    }

    public function add_invoice($obj,$inspectionDetails){

        if($this->db->insert('invoice',$obj)){
            $id = $this->db->insert_id();
            $addOn = array('invoice_id' => $id);
            end($addOn);
            foreach ($inspectionDetails as &$var) {
                $var = array_merge($addOn, $var);
            }

            if ($this->db->insert_batch('invoice_details', $inspectionDetails)) {
                return true;
            }
            return true;
        }

        else return false;

    }

    public function getValidInvoiceById($id)
    {
        $q = $this->db->get_where("invoice",array('id'=>$id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getValidInvoiceItemsWithDetailsById($id)
    {
        $q = $this->db->get_where("invoice_details",array('invoice_id'=>$id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function deleteInvoice($id)
    {
        if ($this->db->delete("invoice", array('id' => $id))) {
            if ($this->db->delete("invoice_details", array('invoice_id' => $id))) {
                return true;
            }
        }
        return FALSE;
    }


}
