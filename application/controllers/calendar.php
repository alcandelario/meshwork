<?php
class Calendar extends Public_Controller {

	public function __construct()
	{
		parent::__construct();
                $this->load->helper('url');
        }
	
    public function index()
	{
            $data['title'] = "Event Calendar - Meshwork Chicago";
            
            $this->load->library('gcalendar_events');
            
                 $data['events'] = $this->gcalendar_events->getEventList(); 
            $data['active']['1'] = 'active_menu_page';
                
            $this->load->view('templates/header', $data);	
            $this->load->view('calendar/index',$data);
            $this->load->view('templates/footer');
        }        
}
?>