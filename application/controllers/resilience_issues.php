<?php
class resilience_issues extends Common_Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('resilience_issues_model');
                $this->load->helper('url'); 
	}

	public function index()
        {
                  $data['title'] = 'Issues Listing';
                 $data['issues'] = $this->resilience_issues_model->get_Issues();
            
                $this->load->view('templates/header', $data);	
                $this->load->view('resilience_issues/index',$data);
                $this->load->view('resilience_issues/create');
                $this->load->view('templates/footer');
        }
        
        public function create()
        {
            $this->load->helper('form');
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('description', 'description', 'required');
            $this->form_validation->set_rules('rootcause', 'rootcause', 'required');
            if($this->form_validation->run() === FALSE)
            {
                $this->resilience_issues_model->set_Issue();
            }            
            
            $data['issues'] = $this->resilience_issues_model->get_Issues();
            $this->load->view('templates/header', $data);	
            $this->load->view('resilience_issues/index',$data);
            $this->load->view('resilience_issues/create');
            $this->load->view('templates/footer');
        }
        
        public function update($ID){
            $this->load->helper('form');
            $this->load->library('form_validation');
            
            $data['title'] = 'Update Issue info';
            
            $this->form_validation->set_rules('issue', 'issue', 'required');
            $this->form_validation->set_rules('rootcause', 'rootcause', 'required');
            
            if ($this->form_validation->run() === FALSE)
            {
                $data['issues'] = $this->resilience_issues_model->get_Issues($ID);
                $this->load->view('templates/header', $data);	
                $this->load->view('resilience_issues/update', $data);
                $this->load->view('templates/footer');
            }
            else
            {
                $this->resilience_issues_model->update_Issue($ID);
                $data['issues'] = $this->resilience_issues_model->get_Issues();
                $this->load->view('templates/header');
                $this->load->view('resilience_issues/index',$data);
                $this->load->view('templates/footer');
            }
        }
        
}


