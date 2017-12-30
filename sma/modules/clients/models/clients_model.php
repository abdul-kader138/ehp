<?php

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
    | This is client module model file.
    | -----------------------------------------------------
    */

class Clients_model extends CI_Model
{


    public function __construct()
    {
        parent::__construct();

    }


    public function getClientTypeByCode($code)
    {
        $q = $this->db->get_where('client_type', array('type_code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function addClient($data)
    {

        if ($this->db->insert("clients", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getClientsByCode($name)
    {
        $q = $this->db->get_where('clients', array('code' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getVendorsByCode($name)
    {
        $q = $this->db->get_where('customers', array('code' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }


    public function addType($data)
    {
        if ($this->db->insert("client_type", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getRQNextAI()
    {
        $this->db->select_max('id');
        $q = $this->db->get('client_type');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "CT" . "-" . sprintf("%09s", $row->id + 1);
        }

        return FALSE;

    }

    public function getRQNextAIClient()
    {
        $this->db->select_max('id');
        $q = $this->db->get('clients');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "CL" . "-" . sprintf("%09s", $row->id + 1);
        }

        return FALSE;

    }


    public function getRQNextAIClientIntake()
    {
        $this->db->select_max('id');
        $q = $this->db->get('client_intake');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "CI" . "-" . sprintf("%09s", $row->id + 1);
        }

        return FALSE;

    }

    public function getTypesByCode($name)
    {
        $q = $this->db->get_where('client_type', array('type_code' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getBuildingAllocationByVendor($id)
    {
        $q = $this->db->get_where('building_allocation', array('building_code' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }


    public function updateType($name, $data = array())
    {
        $this->db->where('type_code', $name);
        if ($this->db->update("client_type", $data)) {
            return true;
        } else {
            return false;
        }
    }


    public function updateClient($name, $data = array())
    {
        $this->db->where('code', $name);
        if ($this->db->update("clients", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteType($name)
    {
        if ($this->db->delete("client_type", array('type_code' => $name))) {
            return true;
        }
        return FALSE;
    }



    public function getTypes()
    {
        $q = $this->db->get("client_type");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }


    public function getClientByNameAndSSN($f_name, $l_name, $ssn)
    {
        $q = $this->db->get_where('clients', array('first_name' => $f_name, 'last_name' => $l_name, 'ssn' => $ssn), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getClientById($c_code)
    {
        $q = $this->db->get_where('client_intake', array('client_code' => $c_code, 'move_out_date' => ""), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function delete($name)
    {
        if ($this->db->delete("clients", array('code' => $name))) {
            return true;
        }
        return FALSE;
    }

    public function delete_intake($data){
        $intake_details=$this->getIntakeByCode($data);
        if($this->db->delete("client_intake", array('code'=>$data))) {
            if ($this->db->update("clients",
                array('isTaggedWithVendor' => 'No', 'updated_by' => USER_NAME, 'updated_date' => date('Y-m-d H:i:s')),
                array('code' => trim($intake_details->client_code)))
            ) {
                if($this->increaseApartmentCapacity($intake_details->apartment_code)) return true;
                else return false;
            }
            return false;
        }
    }

    public function dischargeClient($data,$code){
        $intake_details=$this->getIntakeByCode($code);
        if($this->db->update("client_intake", $data,array('code'=>$code))) {
            if ($this->db->update("clients",
                array('isTaggedWithVendor' => 'No', 'updated_by' => USER_NAME, 'updated_date' => date('Y-m-d H:i:s')),
                array('code' => trim($intake_details->client_code)))
            ) {
                if($this->increaseApartmentCapacity($intake_details->apartment_code)) return true;
                else return false;
            }
            return false;
        }
    }

    public function addClientIntake($data)
    {
        if ($this->db->insert("client_intake", $data)) {
            if ($this->db->update("clients",
                array('isTaggedWithVendor' => 'Yes', 'updated_by' => USER_NAME, 'updated_date' => date('Y-m-d H:i:s')),
                array('code' => trim($data['client_code'])))
            ) {
                if($this->decreaseApartmentCapacity($data['apartment_code'])) return true;
                else return false;
            }
            return false;
        }
        return false;
    }

    public function getAllUnTaggedClients()
    {
        $q = $this->db->get_where("clients", array('isTaggedWithVendor' => 'No'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getAllVendors()
    {
        $q = $this->db->get("customers");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getBuildingByVendorID($vendor_id)
    {
        $vendor_details = $this->getVendorsByCode($vendor_id);
        $q = $this->db->get_where("building_allocation", array('vendor_id' => $vendor_details->id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }


    public function getApartmentByVendorID($vendor_id, $building_id)
    {
        $building_details = $this->getBuildingAllocationByVendor($building_id);
        $this->db->select('bd.level_code,bd.building_code,l.room_code');
        $this->db->from('building_details bd');
        $this->db->join('level l', 'bd.level_code = l.level_code');
        $this->db->join('rooms r', 'l.room_code = r.room_code');
        $this->db->where(array('bd.building_code' => $building_details->building_code, 'r.total_bed_qty >'=>"0"));
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach (($query->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }

        return FALSE;
    }


    public function getApartmentByCode($code)
    {
        $q = $this->db->get_where('rooms', array('room_code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function decreaseApartmentCapacity($code)
    {
        $apartmentDetails = $this->getApartmentByCode($code);
        $bed_qty = $apartmentDetails->total_bed_qty;
        $occupied_qty = $apartmentDetails->bed_occupied;
        $new_bed_qty = ($bed_qty - 1);
        $new_occupied_qty = ($occupied_qty + 1);
        if($new_bed_qty <=0) $vacant_date="";
        else $vacant_date=$apartmentDetails->vacant_date;
        if ($this->db->update("rooms",
            array('total_bed_qty' => $new_bed_qty,
                'updated_by' => USER_NAME,
                'vacant_date' => $vacant_date,
                'updated_date' => date('Y-m-d H:i:s'),
                'bed_occupied' => $new_occupied_qty),
            array("room_code" => $code))
        ) return true;
        return false;
    }

    public function getIntakeByCode($code)
    {
        $q = $this->db->get_where('client_intake', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }


    public function getClientByIntakeCode($code)
    {
        $q = $this->db->get_where('client_intake', array('code' => $code,'move_out_date !='=>""), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function increaseApartmentCapacity($code)
    {
        $apartmentDetails = $this->getApartmentByCode($code);
        $bed_qty = $apartmentDetails->total_bed_qty;
        $occupied_qty = $apartmentDetails->bed_occupied;
        $new_bed_qty = ($bed_qty + 1);
        $new_occupied_qty = ($occupied_qty - 1);
        if($new_bed_qty == 1) $vacant_date=date('Y-m-d');
        else $vacant_date=$apartmentDetails->vacant_date;
        if ($this->db->update("rooms",
            array('total_bed_qty' => $new_bed_qty,
                'updated_by' => USER_NAME,
                'vacant_date' => $vacant_date,
                'updated_date' => date('Y-m-d H:i:s'),
                'bed_occupied' => $new_occupied_qty),
            array("room_code" => $code))
        ) return true;
        return false;
    }
} 