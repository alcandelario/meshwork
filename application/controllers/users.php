<?php
class users extends Common_Auth_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('users_model');
                $this->load->helper('url'); 
                @define("PROFILEPHOTO_BASEPATH","uploaded_media\\photos_profile\\");
  }

  public function index()
        {   
                              $config = array();
                 $config['first_url'] = base_url().index_page().'/users';
                  $config['base_url'] = base_url().index_page()."/users/index/";
                $config['total_rows'] = $this->users_model->countUsers();
                  $config['per_page'] = 10;
               $config['uri_segment'] = 3;
                 $config['num_links'] = 2;
             $config['full_tag_open'] = '<nav id="pagination_nav"><ol class="clearfix">';
            $config['full_tag_close'] = '</ol></nav>';
              $config['num_tag_open'] = "<li>";
             $config['num_tag_close'] = "</li>";
              $config['cur_tag_open'] = "<li id='cur_page'><a href='#pagination_nav'>";
             $config['cur_tag_close'] = "</a></li>";
             $config['next_tag_open'] = "<li>";
            $config['next_tag_close'] = "</li>";
             $config['prev_tag_open'] = "<li>";
            $config['prev_tag_close'] = "</li>";
            
            $this->pagination->initialize($config);
            
            // determines what the offset is for the next message fetch from the dB
               $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
              $this->load->model('users_model');
              
                  $data['title'] = "Contacts - Meshwork Chicago";
                $data['members'] = $this->users_model->get_userInfo(FALSE,FALSE,$config['per_page'],$page);
            $data['active']['3'] = 'active_menu_page';
            
            // get a list of the organizations in order to allow for filtering
            $this->load->model('providers_model');
            $data['provider_select'] = $this->providers_model->get_providerNameAndIDArray();
            
            // create pagination links if needbe
            $data['pagination'] = $this->pagination->create_links();
            
            $this->load->view('templates/header', $data);
            $this->load->view('users/index',$data);
            $this->load->view('templates/footer');
  }

        public function filter()
        {
           // Get the organization we're filtering by
            $ID = $this->input->post('organization');
            $where = FALSE;
            if(stripos($ID,'all') === FALSE)
            {
                $where = 'providerID_users = '.$ID;
            }
                             $config = array();
                 $config['first_url'] = 'users/index/1';
                  $config['base_url'] = base_url().index_page()."/users/index/";
                $config['total_rows'] = $this->users_model->countUsers($where);
                  $config['per_page'] = 10;
               $config['uri_segment'] = 3;
                 $config['num_links'] = 2;
             $config['full_tag_open'] = '<nav id="pagination_nav"><ol class="clearfix">';
            $config['full_tag_close'] = '</ol></nav>';
              $config['num_tag_open'] = "<li>";
             $config['num_tag_close'] = "</li>";
              $config['cur_tag_open'] = "<li id='cur_page'><a href='#pagination_nav'>";
             $config['cur_tag_close'] = "</a></li>";
             $config['next_tag_open'] = "<li>";
            $config['next_tag_close'] = "</li>";
             $config['prev_tag_open'] = "<li>";
            $config['prev_tag_close'] = "</li>";
            
            $this->pagination->initialize($config);
            $seg = $this->uri->segment(3);
            
            // determines what the offset is for the next message fetch from the dB
               $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
               
            // Are we filtering, or do we return EVERYONE
            if(stripos($ID,'default') === FALSE && stripos($ID,'all') === FALSE)
            {
                $data['members'] = $this->users_model->get_userInfo(FALSE,$where,$config['per_page'],$page);
            }
            else
            {
                redirect("users","refresh");
            }
            
            $this->load->model('providers_model');
            $data['selected_provider'] = $ID;  
              $data['provider_select'] = $this->providers_model->get_providerNameAndIDArray();
            
            // create pagination links if needed
            $data['pagination'] = $this->pagination->create_links();
            
            $data['title'] = "Contacts - Meshwork Chicago";
            $this->load->view('templates/header', $data);
            $this->load->view('users/index',$data);
            $this->load->view('templates/footer');
        }
        
        
  public function view($ID)
  {
            $data['member'] = $this->users_model->get_userInfo($ID);
          
            if (empty($data['member']))
            {
                show_404();
            }
            
            $this->load->model('providers_model');
                    
                    $detailed_data = $this->providers_model->get_providerDetailData($data['member']['providerID']);
                    $data['focus'] = $detailed_data['resilience_issues'];
            $data['service_areas'] = $detailed_data['service_areas'];
              
            $data['title'] = $data['member']['first_name']." ".$data['member']['last_name']." - Contact Info";

            $this->load->view('templates/header', $data);
            $this->load->view('users/view', $data);
            $this->load->view('templates/footer');
        }
        
        public function profile($ID) {
            // load the messages model
             $this->load->model('messages_model');
             
                       $data['member'] = $this->users_model->get_userInfo($ID);
                $_SESSION['user_view'] = $data['member'];
            
            if (empty($data['member']))
            {
                show_404();
            }
            
            // set the page title
            $data['title'] = $data['member']['first_name']." ".$data['member']['last_name']." - Contact Info";
             
            // load a handful of this user's messages
                              $config = array();
                 $config['first_url'] = 'users/profile/'.$ID.'/1';
                  $config['base_url'] = base_url().index_page()."/users/profile/".$ID."/";
                $config['total_rows'] = $this->messages_model->countMsgs("userID_messages = ".$ID);
                  $config['per_page'] = 5;
               $config['uri_segment'] = 4;
                 $config['num_links'] = 2;
             $config['full_tag_open'] = '<nav id="pagination_nav"><ol class="clearfix">';
            $config['full_tag_close'] = '</ol></nav>';
              $config['num_tag_open'] = "<li>";
             $config['num_tag_close'] = "</li>";
              $config['cur_tag_open'] = "<li id='cur_page'><a href='#pagination_nav'>";
             $config['cur_tag_close'] = "</a></li>";
             $config['next_tag_open'] = "<li>";
            $config['next_tag_close'] = "</li>";
             $config['prev_tag_open'] = "<li>";
            $config['prev_tag_close'] = "</li>";
          
       
           $this->pagination->initialize($config);
           
           // determines what the offset is for the next message fetch from the dB
           $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
                     
           
           // get a page of all this user's messages and a "replies" summary to those 
           // messages (if any) 
           $messages = $this->messages_model->get_messages(FALSE,$config['per_page'],$page,"userID_messages = ".$ID);
            
               $data['messages'] = $messages['messages'];
           $data['msg_concerns'] = $messages['msg_concerns'];

           if(isset($messages['reply_count']))
           {
             // add the reply counter array if we have any replies to any messages at all
             $data['reply_count'] = $messages['reply_count'];
           }
                  
           $data['pagination'] = $this->pagination->create_links();

           $this->load->view('templates/header', $data);
           $this->load->view('users/profile',$data);
           $this->load->view('messages/index',$data);
           $this->load->view('templates/footer');
        }
        
        public function create()
        {
            // Create member contact info
            
            $this->load->helper('form');
            $this->load->library('form_validation');

            $data['title'] = 'Add New Responder';

            $this->set_form_rules();
             
            if ($this->form_validation->run() === FALSE)
            {
                // we're loading a blank form 
                    $this->load->model('providers_model');
                     
                    $data['provider_select'] = $this->providers_model->get_providerNameAndIDArray();
                    
                    $this->load->view('templates/header', $data); 
                    $this->load->view('users/create',$data);
                    $this->load->view('templates/footer');
            }
            else
            {
                // we have a submitted form
              $data['new_user'] = TRUE;
                       $results = $this->validate_photo($data);
                         $photo = $results['photo'];
                $photo_filename = $results['filename'];
                
                if($photo === TRUE || stripos($photo,"NULL") !== FALSE){
                    $this->users_model->set_userInfo($photo_filename);
                    header('location: '.base_url()."index.php/users");
                }
            }
        }
               
        public function update($ID)
        {
           // Update member contact info
            
            $this->load->helper('form');
            $this->load->library('form_validation');

            $data['title'] = 'Update responder contact info';

            $this->set_form_rules();
             
            if ($this->form_validation->run() === FALSE)
            {
                // we're loading a blank form 
                    $this->load->model('providers_model');
                    
                // get the providers list    
                    $data['provider_select'] = $this->providers_model->get_providerNameAndIDArray();
                    
                // get the data specific to this member    
                    $data['member'] = $this->users_model->get_memberInfo($ID);
                    
                    $this->load->view('templates/header', $data); 
                    $this->load->view('users/update',$data);
                    $this->load->view('templates/footer');

            }
            else
            {
                // we have a submitted form
                $data['member'] = $this->users_model->get_userInfo($ID);
                       $results = $this->validate_photo($data);
                         $photo = $results['photo'];
                $photo_filename = $results['filename'];
                
                if($photo === TRUE || stripos($photo,"NULL") !== FALSE){
                    $this->users_model->update_userInfo($ID,$photo_filename);
                    header('location: '.base_url().'index.php/users/view/'.$data['member']['id']);
                }
            }
            
        }
        
        private function set_form_rules() 
        {
            // $this->form_validation->set_rules('providerID','Provider Name','required');
            $this->form_validation->set_rules('firstname','First Name', 'required');
            $this->form_validation->set_rules('lastname','Last Name', 'required');
            $this->form_validation->set_rules('phone', 'Phone', 'required');
            $this->form_validation->set_rules('email','Email Address','required');
            $this->form_validation->set_rules('has_car','"Has a Car"','required');
        }
        
        private function validate_photo($data)
        {
            $filename = trim($_FILES['profile_photo']['name']);
            if(!empty($filename)){           
                    $ext = explode(".",$_FILES['profile_photo']['name']);
                    $ext = $ext[1];
                }
                else {
                     $ext = FALSE;
                }
                   
                if($ext !== FALSE && isset($data['member'])){
                   $photo_filename = trim($data['member']['firstname']).trim($data['member']['lastname']).".".$ext;
                }
                elseif(isset($data['new_user']) && $ext !== FALSE){
                    $photo_filename = trim($_POST['firstname']).trim($_POST['lastname']).".".$ext;
                }
                else {
                    $photo_filename = '';
                }

                if(!empty($data['member']['profilephoto_filepath']) && !empty($photo_filename)){
                   // try to delete the existing file since we're replacing it
                      $this->load->helper('file');
                      $file = ROOTDIR.PROFILEPHOTO_BASEPATH.$data['member']['profilephoto_filepath'];
                      @unlink($file);
                }
                  
                if(!empty($photo_filename)){
                    // overwrite the file hame so we don't upload  to the photo directory
                    $_FILES['profile_photo']['name'] = $photo_filename;
                   
                    // copy the new photo to the profile photos directory
                    $photo = $this->do_upload("profile_photo");
                    if($photo['upload_data']['is_image'] !== FALSE){
                        $photo = TRUE;
                    }
                }
                else {
                    // no photo was uploaded
                    $photo = "NULL";
                }
                
                return array('photo' => $photo, 'filename' => $photo_filename);
        }
            
        private function do_upload($userfile = '')
  {
      $config['upload_path'] = './'.PROFILEPHOTO_BASEPATH;
    $config['allowed_types'] = 'gif|jpg|jpeg|png';
         $config['max_size'] = '400';
       $config['max_width']  = '1024';
      $config['max_height']  = '768';

    $this->load->library('upload', $config);

    if ( ! $this->upload->do_upload($userfile))
    {
      $error = array('error' => $this->upload->display_errors());
    }
    else
    {                    
                    return array('upload_data' => $this->upload->data());               
    }
  }
}
