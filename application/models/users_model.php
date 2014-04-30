<?php
class Users_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    
    public function countUsers($where = FALSE)
    {
        // get number of total users
        $this->db->select('*');
        $this->db->where('id !=1');
        
        // if we're filtering, add the where clause
        if($where !== FALSE)
        {
            $this->db->where($where);
        }
        
        $this->db->from('users');;
              
        return $this->db->count_all_results();
    }
    
    public function get_userInfo($ID = FALSE,$where_statement = FALSE,$limit = FALSE, $offset = 0)
    {     
        $this->db->select('*');
        $this->db->from('users');
        
        // only need the where clause for viewing one record
        if($ID !== FALSE){
            //$this->db->where(array('users.id' => $ID));
            $this->db->where('users.id ='.$ID.' AND users.id NOT LIKE 1');
        }
        elseif($where_statement !== FALSE)
        {
            // in case we want to filter users by some other criteria other than ID
            $this->db->where($where_statement);
        }
        
        if($ID === FALSE)
        {
           // don't return info on the super user for this app
           $this->db->where('users.id NOT LIKE 1');
        }
        
        // only needed when returning all main messages and not individual ones
        if($ID === FALSE )
        {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->join('providers','users.providerID_users = providers.providerID','left');
        
    // only need the order_by clause when viewing all records
        if($ID === FALSE){
            $this->db->order_by('first_name asc');
        }
        
        $query = $this->db->get();
        
        if($ID === FALSE) 
        {
            // return multiple records in array format
            return $query->result_array();
        }
        else 
        {
            // return one record in array format 
            return $query->row_array();
        }
        
    }
    
    public function get_usersNameAndOrgArray($ID = FALSE)
    {
        // build a multi-dimensional array that returns all users by org name
        // and returns an array with their userID and an inner array of the email and name
        $members = $this->get_userInfo($ID);
        
        $array = array();
            foreach($members as $member)
            {
                $array[$member['name_ofc']][$member['id']] = array($member['email'] => $member['first_name']." ".$member['last_name']." (".$member['name_ofc'].")");
            }
            return $array;
    }
    
    public function set_userInfo($filepath = '')
    {       
    $data = array(
                       'title' => $this->input->post('title'),
                  'first_name' => $this->input->post('firstname'),
                   'last_name' => $this->input->post('lastname'),
                       'phone' => $this->input->post('phone'),
                       'email' => $this->input->post('email'),
                      'street' => $this->input->post('street'),
                        'city' => $this->input->post('city'),
                       'state' => $this->input->post('state'),
                     'zipcode' => $this->input->post('zipcode'),
                     'has_car' => $this->input->post('has_car'),
                    'about_me' => $this->input->post('about_me'),
            'providerID_users' => ($this->input->post('providerID') + 0)
        );
        
        // add the profile photo file name if necessary
        if(!empty($filepath)){
            $data['profilephoto_filepath'] = $filepath;
        }
        return $this->db->insert('users', $data);
    }
    
    public function update_userInfo($ID = 0,$filepath = '')
    {
        $data = array(
                       'title' => $this->input->post('title'),
                  'first_name' => $this->input->post('firstname'),
                   'last_name' => $this->input->post('lastname'),
                       'phone' => $this->input->post('phone'),
                       'email' => $this->input->post('email'),
                      'street' => $this->input->post('street'),
                        'city' => $this->input->post('city'),
                       'state' => $this->input->post('state'),
                     'zipcode' => $this->input->post('zipcode'),
                     'has_car' => $this->input->post('has_car'),
                    'about_me' => $this->input->post('about_me'),
            'providerID_users' => ($this->input->post('providerID') + 0)
        );
        // add the profile photo file name if necessary
        if(!empty($filepath)){
            $data['profilephoto_filepath'] = $filepath;
        }
        $this->db->where('id', $ID);
        return $this->db->update('users',$data);
        
    }
}

