<?php
class providers_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
            $this->load->helper('url');
    }
    
    public function get_providerInfo($ID = FALSE, $where_clause = FALSE,$type=FALSE)
    {               
        if ($ID === FALSE)
	{
            //insert a where clause to filter if needed
            if($where_clause !== FALSE)
            {
                if($type == 'area')
                {
                    $this->db->join('provider_service_areas', 'provider_service_areas.providerID_serviceareas = providers.providerID','left');
                    $this->db->join('service_areas','service_areas.serviceareaID = provider_service_areas.serviceareaID_serviceareas','left');
                }
                elseif($type = 'specialty')
                {
                    $this->db->join('provider_specialties', 'provider_specialties.providerID_specialties = providers.providerID','left');
                    $this->db->join('resilience_issues', 'resilience_issues.issueID = provider_specialties.issueID_specialties','left');
                }
                
                // return all providers filtered by, something
                $this->db->where($where_clause);
            }
            // run the query 
            $this->db->order_by('name_ofc','asc');
            $query = $this->db->get('providers');
            return $query->result_array();
	}
        elseif($ID !== FALSE)
        {
            $where = array('providerID' => $ID);
        }
        
        if($where_clause !== FALSE && $ID !== FALSE)
        {
            $where = $where + $where_clause;
            
        }
            $query = $this->db->get_where('providers',$where);
            return $query->row_array();
        
    }
    
    public function get_providerDetailData($ID)
    {
        // use the providerID supplied to retrieve (as of 7/20/13)
        // - provider resilience issues (aka, provider specialties)
        // - provider service areas
        
        $this->db->select('providerID,service_area_name,resilience_issue');
          
        $this->db->from('providers');
        
        $this->db->where('providerID = '.$ID);
        
        $this->db->join('provider_service_areas', 'provider_service_areas.providerID_serviceareas = providers.providerID','left');
        $this->db->join('service_areas','service_areas.serviceareaID = provider_service_areas.serviceareaID_serviceareas','left');
        $this->db->join('provider_specialties', 'provider_specialties.providerID_specialties = providers.providerID','left');
        $this->db->join('resilience_issues', 'resilience_issues.issueID = provider_specialties.issueID_specialties','left');

          $query = $this->db->get();
        
        $results = $query->result_array();
        
                              $return = array();
             $return['service_areas'] = array();
         $return['resilience_issues'] = array();
        
        foreach($results  as $result)
        {
            if(!in_array($result['service_area_name'], $return['service_areas']))
            {
                $return['service_areas'][] = $result['service_area_name'];
            }
            if(!in_array($result['resilience_issue'], $return['resilience_issues']))
            {
              $return['resilience_issues'][] = $result['resilience_issue'];
            }
        }
        
//            $return['service_areas'] = implode(", ",$return['service_areas']);
//        $return['resilience_issues'] = implode(", ",$return['resilience_issues']);
        
        return $return;
    }
    
    public function get_providerKey($provider_name,$zipcode,$phone)
    {
        $query  = $this->db->get_where('providers',array('name_ofc' => $provider_name,'zipcode_ofc' => $zipcode,'phone_ofc' => $phone));
        return $query->row_array();
    }
    
    public function get_providerNameAndIDArray($ID = FALSE)
    {
        $providers = $this->get_providerInfo($ID);
        $array = array();
        foreach($providers as $provider){
            $array[$provider['providerID']] = $provider['name_ofc']; 
        }
        return $array;
    
    }
    
    public function set_providerInfo()
    {   	
	$data = array(
                        'name_ofc' => $this->input->post('name_ofc'),
                      'street_ofc' => $this->input->post('street_ofc'),
                        'city_ofc' => $this->input->post('city_ofc'),
                       'state_ofc' => $this->input->post('state_ofc'),
                     'zipcode_ofc' => $this->input->post('zipcode_ofc'),
                       'phone_ofc' => $this->input->post('phone_ofc'),
                      'philosophy' => $this->input->post('philosophy'),
                     'website_ofc' => $this->input->post('website_ofc'),
                       'email_ofc' => $this->input->post('email_ofc')   
                );
        // let's geocode the address
              $this->load->library('map_utils');
           $geocode = new map_utils;
              $data = $geocode->geocode_address($data);
        
        // save the provider's specialties and service areas
            $specialties = $this->input->post('specialties');
          $service_areas = $this->input->post('service_areas');
          
	$success = $this->db->insert('providers', $data);
          $query = $this->get_providerKey($this->input->post('name_ofc'),
                                          $this->input->post('zipcode_ofc'),
                                          $this->input->post('phone_ofc')
                                         );
            $key = $query['providerID'];
        // Populate the necessary data in the issues/service areas tables so the controller
        // doesn't have to worry about it
            $this->set_providerSpecialties($key,$specialties);
            $this->set_providerServiceAreas($key,$service_areas);
            
        
    }
    
    public function update_providerInfo($ID = 0)
    {
        $data = array(
                        'name_ofc' => $this->input->post('name_ofc'),
                      'street_ofc' => $this->input->post('street_ofc'),
                        'city_ofc' => $this->input->post('city_ofc'),
                       'state_ofc' => $this->input->post('state_ofc'),
                     'zipcode_ofc' => $this->input->post('zipcode_ofc'),
                       'phone_ofc' => $this->input->post('phone_ofc'),
                      'philosophy' => $this->input->post('philosophy'),
                     'website_ofc' => $this->input->post('website_ofc'),
                       'email_ofc' => $this->input->post('email_ofc')
                );
        
        // let's geocode the address
              $this->load->library('map_utils');
           $geocode = new map_utils;
              $data = $geocode->geocode_address($data);
        
        // save the provider's specialties and service areas
            $specialties = $this->input->post('specialties');
          $service_areas = $this->input->post('service_areas');
        
        $this->db->where('providerID', $ID);
        $this->db->update('providers',$data);
        
        $this->update_providerSpecialties($ID,$specialties);
        $this->update_providerServiceAreas($ID,$service_areas);
    }
    
    public function set_providerSpecialties($key,$issues)
    {
        if(is_array($issues) && count($issues) > 0)
        {
            foreach($issues as $issueID)
            {
                $issueID = $issueID + 0;
                $batch_array[] = array('providerID_specialties' => $key, 'issueID_specialties' => $issueID);
            }

            $this->db->insert_batch('provider_specialties', $batch_array);
        }
        
    }
    
    public function set_providerServiceAreas($key,$areas)
    {
        if(is_array($areas) && count($areas) > 0)
        {
            foreach($areas as $areaID)
            {
                $areaID = $areaID + 0;
                $batch_array[] = array('providerID_serviceareas' => $key, 'serviceareaID_serviceareas' => $areaID);
            }

            $this->db->insert_batch('provider_service_areas',$batch_array);
        }
    }
    
    public function update_providerSpecialties($key,$issues)
    {
        $this->db->where('providerID_specialties = '.$key);
        @$this->db->delete('provider_specialties'); 
        if(is_array($issues) && count($issues) > 0 )
        {
            $this->set_providerSpecialties($key, $issues);
        }
    }
    
    public function update_providerServiceAreas($key,$service_areas)
    {
        $this->db->where('providerID_serviceareas = '.$key);
        @$this->db->delete('provider_service_areas');
        if(is_array($service_areas) && count($service_areas) > 0)
        {
            $this->set_providerServiceAreas($key, $service_areas);
        }
    }
    
    public function set_activeStatus($ID,$active_state)
    {
        $this->db->where('providerID = '.$ID);
        $this->db->update('providers',array('provider_active' => $active_state));
    }
}

