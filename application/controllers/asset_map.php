<?php
class Asset_map extends Public_Controller {

	public function __construct()
	{
		parent::__construct();
                $this->load->database();
                $this->load->helper('url');
                $this->load->model('providers_model');
                $this->load->model('resilience_issues_model');
                $this->load->model('service_areas_model');
                  $this->specialties = $this->resilience_issues_model->get_Issues();
                              $areas = $this->service_areas_model->get_serviceAreas();
                $this->service_areas = $this->service_areas_model->build_dropdownArray($areas);
                //set the map center
                $this->centerLat = 41.875710;
                $this->centerLng = -87.624009;
        }
	
        public function index()
	{
                    $data['title'] = "Resource Map - Meshwork Chicago";
              $data['specialties'] = $this->specialties;
            $data['service_areas'] = $this->service_areas;
                $data['centerlat'] = $this->centerLat;
                $data['centerlng'] = $this->centerLng;
              $data['active']['2'] = 'active_menu_page';
     $data['additional_head_data'] = $this->load->view('asset_map/maps_api_js_template',$data,TRUE);
            
          $data['index_view'] = 'TRUE';
            $data['bg_image'] = 'asset_map_view';
            $this->load->view('templates/header', $data);	
            $this->load->view('asset_map/index');
            $this->load->view('templates/footer');
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
                    $data['title'] = "Resource Map";
              $data['specialties'] = $this->specialties;   
            $data['service_areas'] = $this->service_areas;
                $data['centerlat'] = $this->centerLat;
                $data['centerlng'] = $this->centerLng;
              $data['active']['2'] = 'active_menu_page';
                
                 $data['bg_image'] = 'asset_map_view';      
                    $data['title'] = "Resource Map - Meshwork Chicago";
     $data['additional_head_data'] = $this->load->view('asset_map/maps_api_js_template',$data,TRUE);
           // disable any control buttons in the following view
        $data['disable_admin_btn'] = 'true';
           $data['provider_index'] = $this->load->view('providers/index',$data,TRUE);
       
             $this->load->view('templates/header', $data);
             $this->load->view('asset_map/index');
             $this->load->view('templates/footer');
        }
 }
?>
