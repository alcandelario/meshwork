<?php
//
class messages extends Common_Auth_Controller {

	public function __construct()
	{
		parent::__construct();
                $this->load->model('messages_model');
                $this->load->model('events_model');
		
        }

	public function index()
        {
                              $config = array();
                 $config['first_url'] = 'messages/index/1';
                  $config['base_url'] = base_url().index_page()."/messages/index/";
                $config['total_rows'] = $this->messages_model->countMsgs();
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
           
               // retrieve the next upcoming event in case we want to display it in th header
               $latest = $this->events_model->get_latestEvent();
               if(count($latest) > 0)
               {
                    $data['upcoming_summary'] = $latest['summary'];
                       $data['upcoming_date'] = $latest['calendar_date'];
                       
                       // refresh the $_SESSION variables used throughout the app
                       $_SESSION['upcoming_summary'] = $latest['summary'];
                          $_SESSION['upcoming_date'] = $latest['calendar_date'];
               }
               else 
               {
                   unset($_SESSION['upcoming_summary']);
                   unset($_SESSION['upcoming_date']);
               }
               
               // determines what the offset is for the next message fetch from the dB
               $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
               
               // returns two arrays: one with all the message info for this page
               // and another which summarizes how many replies each message has (if any) 
               $messages = $this->messages_model->get_messages(FALSE,$config['per_page'],$page);
            
                         $data['title'] = 'News Feed - Meshwork';
                      $data['messages'] = $messages['messages'];
                  $data['msg_concerns'] = $messages['msg_concerns'];
                  
                  if(isset($messages['reply_count']))
                  {
                    // add the reply counter array if we have any replies to any messages at all
                    $data['reply_count'] = $messages['reply_count'];
                  }
                  
                  $data['pagination'] = $this->pagination->create_links();
                 
                // perhpas in the future, return the last 3 replies for each message
                // and use AJAX to retrieve all replies for a given messagge if necessary
               
                //$data['replies'] = $this->messages_model->get_Repliesmessages['replies'];
            $data['active']['0'] = 'active_home';
                  
            $this->load->view('templates/header', $data);
            $this->load->view('messages/index',$data);
            $this->load->view('templates/footer');

        }
        
        public function post()
        {
            $this->form_validation->set_rules('message', 'Message', 'required');
            $this->form_validation->set_rules('msg_concerns','Focus','required');
            
            if ($this->form_validation->run() === FALSE)
            {
                $this->load->model('users_model');
                $this->load->model('resilience_issues_model');
            
                            $data['title'] = "Post a message!";
                     $data['users_select'] = $this->users_model->get_usersNameAndOrgArray();
                    $data['issues_select'] = $this->resilience_issues_model->get_Issues();
             $data['google_calendar_form'] = $this->load->view('calendar/google_calendar_event','',TRUE);
            $data['user_multiselect_form'] = $this->load->view('users/users_multiselect',$data,TRUE);
               
               // enable the jquery datetime plugin and theme
                $data['time_picker_en'] = true;    
               
                $this->load->view('templates/header', $data);	
                $this->load->view('messages/post', $data);
                $this->load->view('templates/footer');
            }
            else 
            {
                $this->messages_model->set_message();

                // go to the home page controller though, not sure if this is the best thing to do
                redirect('/');
            }
        }
        
        public function edit($ID)
        {
            @session_start();
      
            $this->form_validation->set_rules('message', 'Message', 'required');
            $this->form_validation->set_rules('msg_concerns','Focus','required');
            
            if ($this->form_validation->run() === FALSE)
            {
                $this->load->model('users_model');
                $this->load->model('resilience_issues_model');
                            
                            $data['title'] = "Edit Message!";
                     $data['users_select'] = $this->users_model->get_usersNameAndOrgArray();
                    $data['issues_select'] = $this->resilience_issues_model->get_Issues();
                                  $message = $this->messages_model->get_messages($ID);
                          $data['issueID'] = $this->resilience_issues_model->get_issueID($message['msg_concerns']);
                          $data['message'] = $message['message'];
                
                // get any replies to this message 
                if(isset($message['replies']))
                {
                    $data['replies'] = $message['replies'];
                }
                
                if(isset($data['message']['calendar_date']))
                {
                    $data['event_time'] = $this->parse_eventTime($data['message']['calendar_date']);
                    
                    // retrieve the current event data as an array, not an object
                    $data['event'] = $this->messages_model->get_eventData($data['message']['googlecalendar_id'],FALSE);
                }
                
             // enable the jquery datetime picker
             $data['time_picker_en'] = true;    
            
             $data['google_calendar_form'] = $this->load->view('calendar/google_calendar_event',$data,TRUE);
            $data['user_multiselect_form'] = $this->load->view('users/users_multiselect',$data,TRUE);
            
            $_SESSION['calID'] = $data['message']['googlecalendar_id'];
                
                $this->load->view('templates/header', $data);	
                $this->load->view('messages/edit', $data);
                $this->load->view('messages/view_replies',$data);
                $this->load->view('templates/footer');
            }
            else 
            {
                $this->messages_model->update_message($ID, $_SESSION['calID']);

                // go to the home page controller though, not sure if this is the best thing to do
                //header('location: '.base_url()."index.php/messages/reply/".$ID);
                redirect('/');
            }
            
        }
        
        public function parse_eventTime($dateTime)
        {
            $return = array();
            
            $date_time = explode(' ',$dateTime);
                 $date = $date_time[0];
                 $time = $date_time[1];
            
                         $date_chunks = explode('-',$date);
            $return['selected_month'] = $date_chunks[1]+0;   //cast to an int to remove the leading 0
              $return['selected_day'] = $date_chunks[2]+0;
            
              $time_chunks = explode(':', $time);
                     $hour = $time_chunks[0];
                   $minute = $time_chunks[1];
              
             $return['selected_time'] = $hour.":".$minute;
                   
             return $return;
        }
        
        public function reply($ID)
        {
            $this->load->helper('form');
            $this->load->library('form_validation');

            $this->form_validation->set_rules('message','Reply', 'required');
            
            if ($this->form_validation->run() === FALSE)
            {
                             $message = $this->messages_model->get_messages($ID);
                     $data['message'] = $message['message'];
                $data['msg_concerns'] = $message['msg_concerns'];
                  
                if(isset($message['replies']))
                {
                    $data['replies'] = $message['replies'];
                }
                $data['title'] = "Reply to ".$message['message']['first_name'];
                $this->load->view('templates/header', $data);
                $this->load->view('messages/view', $data);
                $this->load->view('messages/reply', $data);
                $this->load->view('messages/view_replies',$data);
                $this->load->view('templates/footer');
            }
            else 
            {
                $this->messages_model->set_message();
                redirect("messages/reply/".$ID,'refresh');
                
            }
         }
         
        public function view($ID)
        {
            $data['title'] = "Comment";
            
            $message = $this->messages_model->get_messages($ID);
            $data['message'] = $message['message'];
            $data['replies'] = $message['replies'];
               
            $this->load->view('templates/header', $data);
            $this->load->view('messages/view', $data);
            $this->load->view('templates/footer');
        }
        
        public function delete($ID)
        {
            // check to see if logged in user requesting the delete is the same user who wrote the message
            
            $this->messages_model->delete_message($ID,$this->data['id']);
            redirect('/');
        }
}

