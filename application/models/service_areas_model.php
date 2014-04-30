<?php
class service_areas_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
            $this->load->helper('url');
    }
    
    public function get_serviceAreas($ID = FALSE)
    {
	if ($ID === FALSE)
	{
            // return ALL service areas
            $this->db->order_by("service_area_name", "asc"); 
            $query = $this->db->get('service_areas');
            return $query->result_array();
	}
        elseif(is_array($ID)){
            // return some subset of service areas
            $this->db->order_by("service_area_name", "asc"); 
            $this->db->where_in('serviceareaID',$ID);
            $query = $this->db->get('service_areas');
            $result = $query->result_array();
            if(count($result) > 0){
                foreach($result as $issue){
                    $return[] = $issue['service_area_name'];
                }
                $issues = implode(',',$return);
                return $issues;
            }
            else {
                return "";
            }
        }
        else {
            // return ONE service area
            $this->db->select('serviceareaID, service_area_name');
            $this->db->where('serviceareaID = '.$ID);
            $query = $this->db->get('service_areas');
            $result = $query->row_array();
            $result = array($result['serviceareaID'] => $result['service_area_name']);
            return $result;
        }
    }
    
    public function build_dropdownArray($service_areas)
    {
        $return = array();
        foreach($service_areas as $key => $area)
        {
            $return[$area['serviceareaID']] = $area['service_area_name'];
        }
        return $return;
    }
    
    public function set_serviceAreaInfo()
    {   	
	$data = array(
                        
        );
      
	return $this->db->insert('service_areas', $data);
    }
    
    public function update_serviceAreaInfo($ID = 0)
    {
        $data = array(
		
            
                );
        
        $this->db->where('serviceAreaID', $ID);
        return $this->db->update('service_areas',$data);
        
    }
    
    public function set_providerServiceAreas()
    {
        $issues = $this->input->post('service_areas');
        
    }
}

