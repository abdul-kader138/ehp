<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


/*
    | -----------------------------------------------------
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
        if ($this->db->insert('deficiency_category', $data)) return true;
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

    public function getRQNextAIInspection()
    {
        $this->db->select_max('id');
        $q = $this->db->get('inspection');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return sprintf("%07s", $row->id + 1);
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
        if ($this->db->insert('deficiency_details', $data)) return true;
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

    public function closeInspection($name)
    {
        if ($this->db->update("inspection",array(
            'updated_by' => USER_NAME,
            'close_status'=>'Yes',
            'updated_date' => date('Y-m-d H:i:s')
        ), array('inspection_code' => $name))) {
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
        if ($this->db->insert('deficiency_concern', $data)) return true;
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


    public function getAllCustomers()
    {
        $q = $this->db->get('customers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllBuildings()
    {
        $q = $this->db->get('building');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }


    public function getRoomsNames($term,$code)
    {
        $this->db->select('r.room_name')->limit('20');
        $this->db->from('building b');
        $this->db->join('building_details bd', 'b.building_code = bd.building_code', 'left');
        $this->db->join('level l', 'bd.level_code = l.level_code', 'left');
        $this->db->join('rooms r', 'l.room_code = r.room_code', 'left');
        $this->db->like('r.room_code', $term, 'both');
        $this->db->where('b.building_code', $code);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
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

    public function getAllCategory()
    {

        $this->db->select('*');
        $this->db->from('deficiency_category');
        $this->db->order_by('category_name','ASC');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllConcern()
    {
        $q = $this->db->get('deficiency_concern');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getDetailsInfoByID($id)
    {
        {
            $this->db->select('*');
            $this->db->from('deficiency_details');
            $this->db->where(array('deficiency_details.details_code' => $id));
            $this->db->join('deficiency_concern c', 'deficiency_details.concern_code = c.concern_code', 'left');
            $q = $this->db->get();
//            $q = $this->db->get_where('deficiency_details', array('details_code' => $id), 1);
            if ($q->num_rows() > 0) {
                echo json_encode($q->row());
            }

            echo "";

        }

    }

    public function getDetailsByID($id)
    {
        $this->db->select('deficiency_details.details_code,deficiency_details.details_name');
        $this->db->from('deficiency_details');
        $this->db->where(array('category_code' => $id));
        $this->db->order_by('deficiency_details.details_name','ASC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach (($query->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }

        return FALSE;
    }

    public function addInspection($inspection, $inspectionDetails)
    {
        if ($this->db->insert('inspection', $inspection)) {
            $id = $this->db->insert_id();
            $addOn = array('inspection_id' => $id);
            end($addOn);
            foreach ($inspectionDetails as &$var) {
                $var = array_merge($addOn, $var);
            }

            if ($this->db->insert_batch('inspection_details', $inspectionDetails)) {
                return true;
            }
            return true;
        }

        return false;
    }


    public function updateInspection($inspection, $previousData,$inspectionDetails)
    {
        $new_inspected_area=$previousData->inspected_area +$inspection['inspected_area'];
        $new_weight=$previousData->total_weight +$inspection['total_weight'];
        $new_deficiency=$previousData->total_deficiency +$inspection['total_deficiency'];
        $id=$previousData->id;
        if ($this->db->update('inspection',
            array(
                'total_weight' => $new_weight,
                'total_deficiency' => $new_deficiency,
                'inspected_area' => $new_inspected_area,
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s'))
            , array('id'=>$id))){
            $addOn = array('inspection_id' => $id);
            end($addOn);
            foreach ($inspectionDetails as &$var) {
                $var = array_merge($addOn, $var);
            }
            if ($this->db->insert_batch('inspection_details', $inspectionDetails)) {
                return true;
            }
            return true;
        }

        return false;
    }
    public function getCustomerByID($id)
    {

        $q = $this->db->get_where('customers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;

    }

    public function getAllInspectionDetails($id)

    {
        $this->db->select('idc.date,idc.inspection_code,idc.apartment_code,idc.building_code,idc.vendor_code,idc.weight,idc.comments_id,idc.concern_id,dca.category_name,dcd.details_name,idc.comments_id,c.name');
        $this->db->from('inspection_details idc');
//        $this->db->join('deficiency_concern dcn', 'idc.concern_id = dcn.concern_code', 'left');
        $this->db->join('deficiency_category dca', 'idc.category_id = dca.category_code', 'left');
        $this->db->join('deficiency_details dcd', 'idc.details_id = dcd.details_code', 'left');
        $this->db->join('customers c', 'idc.vendor_code = c.id', 'left');
        $this->db->where('idc.inspection_code', $id);
        $this->db->order_by('idc.id', 'ASC');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $value[] = $row;
            }
            return $value;
        }
    }

    public function getAllConcernAndWeight($id)

    {
        $this->db->select('COUNT(concern_id) as count,concern_id');
        $this->db->from('inspection_details idc');
        $this->db->where('idc.inspection_code', $id);
        $this->db->group_by('idc.inspection_code,idc.concern_id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $value[] = $row;
            }
            return $value;
        }
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


    public function getAllInspection($id)
    {

        $this->db->select('*,inspection.created_by as inspector');
        $this->db->from('inspection');
        $this->db->join('building b', 'inspection.building_code = b.building_code', 'left');
        $this->db->where('inspection.inspection_code', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getVendorsByCode($name)
    {
        $q = $this->db->get_where('customers', array('id' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllInspectionApt($id)

    {
        $this->db->select('COUNT(apartment_code) as num');
        $this->db->from('inspection_details idc');
        $this->db->where('idc.inspection_code', $id);
        $this->db->group_by('idc.inspection_code');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $value[] = $row;
            }
            return $value;
        }
    }



    public function getDetailsInfoByCode($code)
    {
        {
            $q = $this->db->get_where('inspection', array('inspection_code' => $code), 1);
            if ($q->num_rows() > 0) {
                return $q->row();
            }
            return false;

        }

    }
}