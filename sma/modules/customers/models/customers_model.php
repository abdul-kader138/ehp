<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
| -----------------------------------------------------
| PRODUCT NAME: 	STOCK MANAGER ADVANCE 
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
| MODULE: 			Customers
| -----------------------------------------------------
| This is customers module's model file.
| -----------------------------------------------------
*/


class Customers_model extends CI_Model
{
	
	
	public function __construct()
	{
		parent::__construct();

	}
	
	public function getAllCustomers() 
	{
		$q = $this->db->get('customers');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function customers_count() {
        return $this->db->count_all("customers");
    }

    public function fetch_customers($limit, $start) {
        $this->db->limit($limit, $start);
		$this->db->order_by("id", "desc"); 
        $query = $this->db->get("customers");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
	
	public function getCustomerByID($id) 
	{

		$q = $this->db->get_where('customers', array('id' => $id), 1); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;

	}
	
	public function getCustomerByEmail($email) 
	{

		$q = $this->db->get_where('customers', array('email' => $email), 1); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;

	}
	
	public function addCustomer($data = array())
	{
		if($this->db->insert('customers', $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function updateCustomer($id, $data = array())
	{
		
		if($this->db->update('customers', $data,array('id'=>$id))) {
			return true;
		} else {
			return false;
		}
	}
	
	public function add_customers($data = array())
	{
		
		if($this->db->insert_batch('customers', $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function deleteCustomer($id) 
	{
		if($this->db->delete('customers', array('id' => $id))) {
			return true;
		}
	return FALSE;
	}
	
	public function getCustomerNames($term)
    {
	
	   
   		$q = $this->db->query("SELECT id, name FROM customers WHERE name LIKE '%{$term}%' OR cf4 = '$term' Limit 1");
   		
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data; 
		}
    }


    public function getAllDiscounts() 
	{
		$q = $this->db->get('discounts');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}

	public function getDiscountByID($id) 
	{

		$q = $this->db->get_where('discounts', array('id' => $id), 1); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;

	}

    public function getRQNextAI()
    {
        $this->db->select_max('id');
        $q = $this->db->get('customers');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return "V" . "-" . sprintf("%08s", $row->id + 1);
        }

        return FALSE;

    }

}
