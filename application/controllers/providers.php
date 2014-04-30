<?php
class Providers extends Common_Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('providers_model');
                $this->load->model('resilience_issues_model');
                $this->load->model('service_areas_model');
                $this->load->helper('url'); 
                  $this->specialties = $this->resilience_issues_model->get_Issues();
                              $areas = $this->service_areas_model->get_serviceAreas();
                $this->service_areas = $this->service_areas_model->build_dropdownArray($areas);
	}

	public function index()
        {
               $data['providers'] = $this->providers_model->get_providerInfo();
             $data['specialties'] = $this->specialties;
           $data['service_areas'] = $this->service_areas;
                   $data['title'] = "Providers - Meshwork";
         
         // show a drop down form filter in this view
         $data['provider_filter'] = true; 
         
            $this->load->view('templates/header', $data);	
            $this->load->view('providers/index.php');
            $this->load->view('templates/footer');
	}

        public function view($ID)
	{
            $data['providers'] = $this->providers_model->get_providerInfo($ID);
            
            if (empty($data['providers']))
            {
                show_404();
            }
            
            $detailed_data = $this->providers_model->get_providerDetailData($ID);
            
              $data['providers']['specialties'] = $detailed_data['resilience_issues'];
            $data['providers']['service_areas'] = $detailed_data['service_areas'];
                                 $data['title'] = $data['providers']['name_ofc'];

            $this->load->view('templates/header', $data);
            $this->load->view('providers/view', $data);
            $this->load->view('templates/footer');
                
        }
        
        public function create()
        {
            // CREATE A NEW PROVIDER PROFILE
            
            $this->load->helper('form');
            $this->load->library('form_validation');

            $data['title'] = 'Enter new provider info';

            $this->form_validation->set_rules('name_ofc', 'Name', 'required');
            $this->form_validation->set_rules('phone_ofc', 'Phone', 'required');
            $this->form_validation->set_rules('philosophy', 'Mission statement', 'required');

            if ($this->form_validation->run() === FALSE)
            {
                    $data['service_areas'] = $this->service_areas_model->get_serviceAreas();
                      $data['specialties'] = $this->resilience_issues_model->get_Issues();
                   
               $data['service_areas_template_output'] = $this->load->view('templates/service_areas',$data,TRUE);
                      $data['issues_template_output'] = $this->load->view('templates/resilience_issues',$data,TRUE);
                    
                    $this->load->view('templates/header', $data);	
                    $this->load->view('providers/create',$data);
                    $this->load->view('templates/footer');

            }
            else
            {
                $this->providers_model->set_providerInfo();
                header('location: '.base_url().index_page().'/providers');
            }
        }
        
        public function update($ID)
        {
           // UPDATE THE PROVIDER PROFILE 
            
            $this->load->helper('form');
            $this->load->library('form_validation');

            $data['title'] = 'Update provider info - Meshwork';
            
            $this->form_validation->set_rules('name_ofc', 'Name', 'required');
            $this->form_validation->set_rules('phone_ofc', 'phone', 'required');
            $this->form_validation->set_rules('philosophy', 'solutions', 'required');

            if ($this->form_validation->run() === FALSE)
            {
                $data['providers'] = $this->providers_model->get_providerInfo($ID);
                    $detailed_data = $this->providers_model->get_providerDetailData($ID);
               
                $data['service_areas'] = $this->service_areas_model->get_serviceAreas();
                  $data['specialties'] = $this->resilience_issues_model->get_Issues();
                
                $data['checked_service_areas'] = $detailed_data['service_areas'];
                       $data['checked_issues'] = $detailed_data['resilience_issues'];
                   
                $data['service_areas_template_output'] = $this->load->view('templates/service_areas',$data,TRUE);
                       $data['issues_template_output'] = $this->load->view('templates/resilience_issues',$data,TRUE);
                
                $this->load->view('templates/header', $data);	
                $this->load->view('providers/update', $data);
                $this->load->view('templates/footer');
            }
            else
            {
                $this->providers_model->update_providerInfo($ID);
               
                // view this specific provider
                header('location: '.base_url().index_page().'/providers/view/'.$ID);
            }
       }
       
        public function set_active($ID)
        {
            $active_state = $this->uri->segment(4) + 0;
            $this->providers_model->set_activeStatus($ID,$active_state);
            
            // view this specific provider
            header('location: '.base_url().index_page().'/providers/view/'.$ID);

        }
        
        public function filter()
	{
                $specialty = $this->input->post('specialty');
             $service_area = $this->input->post('service_area');
             
             // do we filter by organization specialty, or their service area
             if(!empty($specialty) && stripos($specialty,'all') === FALSE)
             {
                $where = 'issueID_specialties = '.$specialty;
                $issue = $this->resilience_issues_model->get_Issues($specialty);
                 $type = 'specialty';
                
                foreach($issue as $key => $issue)
                {
                    $data['selected_issue'] = $issue;
                    break;
                }
             }
             
             if(stripos($specialty,'all') !== FALSE){
                 $where = FALSE;
                  $type = FALSE;
             }
             
             if(!empty($service_area))
             {
                    $where = 'serviceareaID_serviceareas = '.$service_area;
                     $area = $this->service_areas_model->get_serviceAreas($service_area);
                     $type = 'area';
                     foreach($area as $key => $area)
                     {
                         $data['selected_area'] = $area;
                     }
             }
             elseif(empty($service_area) && empty($specialty))
             {
                 redirect(base_url().index_page().'/asset_map');
             }
             
             // get our providers based on the filter criteria
             $providers = $this->providers_model->get_providerInfo(FALSE, $where, $type); 
                
             // use the google geocoding api to turn our addresses into latitude/longitude coordinates
                $data['providers'] = $providers;
                    $data['title'] = "Providers - Meshwork";
              $data['specialties'] = $this->specialties;   
            $data['service_areas'] = $this->service_areas;
          
            // disable any control buttons in the following view
        $data['disable_admin_btn'] = 'true';
           $data['provider_index'] = $this->load->view('providers/index',$data,TRUE);
          
          //show the drop-down filter in this view 
          $data['provider_filter'] = true;
           
             $this->load->view('templates/header', $data);
             $this->load->view('providers/index');
             $this->load->view('templates/footer');
        }
       
}
