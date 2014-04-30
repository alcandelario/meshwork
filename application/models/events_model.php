<?php
class events_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
            
    }
    
    public function get_latestEvent()
    {
        $this->db->where('calendar_date > "'.date('Y-m-d H:i:s').'"');
        $this->db->order_by('calendar_date','asc');
        $this->db->limit(1);
        $query = $this->db->get('events');
        return $query->row_array();
    }
    
  
}

