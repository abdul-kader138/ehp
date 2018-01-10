<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Home_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

	}
	

	function get_calendar_data($year, $month) {
		
		$query = $this->db->select('date, data')->from('calendar')
			->like('date', "$year-$month", 'after')->get();
			
		$cal_data = array();
		
		foreach ($query->result() as $row) {
			$day = (int)substr($row->date,8,2);
			$cal_data[$day] = str_replace("|", "<br>", html_entity_decode($row->data));
		}
		
		return $cal_data;
		
	}
	
	public function updateComment($comment)
	{
			if($this->db->update('comment', array('comment' => $comment))) {
			return true;
		}
		return false;
	}
	
	public function getComment()
	{
		$q = $this->db->get('comment'); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;
	}
	


    public function topProducts1()
    {
        $m = date('Y-m');
        $this->db->select('sum(rooms.bed_occupied) as occupied, (sum(rooms.total_bed_qty) - sum(rooms.bed_occupied)) as capacity')
            ->from('building')
            ->join('building_details', 'building.building_code=building_details.building_code', 'left')
            ->join('level', 'building_details.level_code=level.level_code', 'left')
            ->join('rooms', 'level.room_code=rooms.room_code', 'left')
            ->where('building.isTaggedWithVendor','Yes');
        $q = $this->db->get();
        if($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
	
}

/* End of file home_model.php */ 
/* Location: ./sma/modules/home/models/home_model.php */
