<?php
class resilience_issues_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
            $this->load->helper('url');
    }
    
    public function get_Issues($ID = FALSE)
    {
        $issues = array();
        $this->db->select('issueID,resilience_issue');
        if($ID !== FALSE){
            // return specific items
            $this->db->where('issueID',$ID);
        }
        
        // return ALL issues
           $this->db->order_by("resilience_issue", "asc");
          $query = $this->db->get('resilience_issues');
        $results = $query->result_array();
        
        // extract the data into a key/value array
        foreach($results as $issue){
            $issues[$issue['issueID']] = $issue['resilience_issue'];
        }
            
        return $issues;
    }
    
    public function get_IssuesAsString($ID = FALSE)
    {
        $issues = $this->get_Issues($ID);
        
        return implode(',',$issues);
    }
    
    public function set_Issue()
    {   	
        $issue = $this->input->post('resilience_issue');
        $cause = $this->input->post('rootcausetheory');
        if(!empty($issue)){
            $data = array('resilience_issue' => $issue );
        }
        if(!empty($cause)){
            $data['rootcausetheory'] = $cause; 
        }
	
	return $this->db->insert('resilience_issues', $data);
    }
    
    public function update_Issue($ID = 0)
    {
        $data = array(
	     'resilience_issue' => $this->input->post('resilience_issue'),
              'rootcausetheory' => $this->input->post('rootcausetheory'),
              );
        $issue = $this->input->post('resilience_issue');
        $cause = $this->input->post('rootcausetheory');
        if(!empty($issue)){
            $data = array('resilience_issue' => $issue );
        }
        if(!empty($cause)){
            $data['rootcausetheory'] = $cause; 
        }
        
        $this->db->where('issueID', $ID);
        return $this->db->update('resilience_issues',$data);
    }
    
    public function get_issueID($issue_array)
    {
        foreach($issue_array as $id => $issue)
        {
            //just grabbing the $issue variable
        }
        if(isset($issue) && !empty($issue))
        {
            $this->db->select('issueID');
            $this->db->where('resilience_issue',$issue);
            $query = $this->db->get('resilience_issues'); 
            return $query->row_array();
        }
        else return array('issueID' => '');
    }
    
}

