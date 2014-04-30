<?php
class messages_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
            $this->load->library('gcalendar_events');
    }
    
    public function countMsgs($where = FALSE)
    {
        // get number of total messages for pagination configuration
        $this->db->select('messageID');
        $this->db->from('messages');
        $this->db->where('replyTo_messageID IS NULL');
        
        if($where !== FALSE)
        {
            $this->db->where($where);
        }
        
        return $this->db->count_all_results();
    }
    
    public function get_Replies($ID, $select = FALSE, $where = FALSE, $limit = FALSE, $offset = NULL)
    {
        // fetch all the replies that are implied in the $or_where_string
        // we allow customization of the select string for fast lookups in 
        // order to count the number of replies that each message has
        
        if($select === FALSE)
        {
            $this->db->select('message, first_name, last_name,email,
                          id, messageID, replyTo_messageID, messagedate,
                          name_ofc,profilephoto_filepath,googlecalendar_id,calendar_date');
        }
        else 
        {
            $this->db->select($select);
        }
        $this->db->from('messages');
        
        // $where can be any of the formats that this CI method accepts
        if($where !== FALSE)
        {
            $this->db->where($where);
        }
        
        if($limit !== FALSE || ($limit !== FALSE && $offset !== NULL))
        {
            if(!empty($offset))
            {
                $this->db->limit($limit,$offset);
            }
            else
            {
                $this->db->limit($limit);
            }
        }

        // lets join our member/provider table info as well (if necessary)
        //$this->db->join('events','events.messageID_events = messages.messageID','left');
        $this->db->join('users','uaers.id = messages.userID_messages');
        $this->db->join('providers','providers.providerID = users.providerID_users','left');
                
        // order results from oldest to newest to mimic the flow of a natural conversation
        $this->db->order_by('messagedate','asc');
                
        $query = $this->db->get();
        
        return $query->result_array();
    }

    public function get_messages($ID = FALSE,$limit = FALSE, $offset = 0, $where = FALSE)
    {
        /*
         * Retrieve messages with an optional limit and offset parameter for 
         * pagination 
         */
          $this->db->select('id, messageID, message, messagedate, 
                             first_name, last_name,email,profilephoto_filepath,
                             replyTo_messageID,name_ofc,googlecalendar_id,calendar_date,summary'
                            );
          $this->db->from('messages');
        
        // if only viewing one message, let's get all the replies for it as well
        if($ID !== FALSE)
        {
              $this->db->where("messageID = ".$ID." OR replyTo_messageID = ".$ID);
        }
        else 
        {
            $this->db->where('replyTo_messageID IS NULL AND privatemsg_userID IS NULL');
        }
        
        if($where !== FALSE)
        {
            // add any additional filtering (i.e. get all messages for one user)
            $this->db->where($where);
        } 
        
        
        // lets join our member/provider/message_concerns/resilience issues table also
          $this->db->join('events','events.messageID_events = messages.messageID','left');
          $this->db->join('users','users.id = messages.userID_messages');
          $this->db->join('providers','providers.providerID = users.providerID_users','left');
         
        // order results from newest to oldest if we're not getting replies
        if($ID === FALSE)
        {
            $this->db->order_by('messagedate','desc');
        }
        else
        {
            // lets order everything from oldest to newest since we have replies in our result.
            // better to order messages replies from oldest to newest to mimic a conversation
            $this->db->order_by('messagedate','asc');
        }
        
        // only needed when returning all main messages and not individual ones
        if($ID === FALSE)
        {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        
        $messages['msg_concerns'] = $this->get_msgConcerns($ID);
            
        // if we're only returning one message, let's filter out the replies into a separate array
        if($ID !== FALSE)
        {
             $results = $query->result_array();
            $filtered = $this->filterReplies($results);
        
            $messages['message'] = $filtered['message'];
            if(isset($filtered['replies']))
            {
                $messages['replies'] = $filtered['replies'];
            }
        }       
        else
        {
            //let's just get our messages and a summary of how many replies each message has
              $messages['messages'] = $query->result_array();
           $messages['reply_count'] = $this->get_RepliesSummary($messages['messages']);
        }
             
        return $messages;
    }
    
    public function get_msgConcerns($ID = FALSE)
    {
          $this->db->join('resilience_issues' , 'message_concerns.issueID_concerns = resilience_issues.issueID');
         if($ID !== FALSE )
         {
             $this->db->where('messageID_concerns = '.$ID);
         }
          $query = $this->db->get('message_concerns');
        $results = $query->result_array();
         $return = array();
        
        foreach($results as $result)
        {
            if( !isset($result['messageID_concerns'], $return[$result['messageID_concerns']]) )
            {
                $array = array($result['resilience_issue']);
                $return[$result['messageID_concerns']] = $array; 
            }
            else
            {
                $return[$result['messageID_concerns']][] = $result['resilience_issue'];
            }
        }
        
        foreach($return as $key => $array)
        {
            $string = implode(' , ', $array);
            $return[$key] = $string;
        }
        return $return;
    }
    
    public function filterReplies($messages)
    {
        $return = array();
        foreach($messages as $message)
        {
            if(!isset($message['replyTo_messageID']))
            {
                $return['message'] = $message;
            }
            else{
                $return['replies'][] = $message;
            }
        }
        return $return;
    }
    
    public function get_RepliesSummary($messages) 
    {
        $array = array();
        foreach($messages as $message)
        {
            // build each part of the "WHERE" clause
            $array[] = "replyTo_messageID = ".$message['messageID'];
        }
        // bring them all together in the final WHERE clause
        $where = implode(' OR ',$array);
        
        // get number of total messages for pagination configuration
       if(count($array) > 0)
       {
           $this->db->select('replyTo_messageID');
           $this->db->from('messages');
           $this->db->where($where);
        
           $query = $this->db->get();
          $result = $query->result_array();
        
          // parse the results into an array that is "messageID" -> $numberOfReplies
          return $this->buildReplyCountArray($result);
       }
    }
    
    private function buildReplyCountArray($results)
    {
        // split results array into main messages, replies to those
        // messages and create an array that shows how many 
        // replies each main message has (if any)
        
              $replies = array();
        $reply_counter = array();
        
        foreach($results as $result){
            if(isset($result['replyTo_messageID']))
            {
                // keep a count of how many replies each message has
                if(!isset($reply_counter[$result['replyTo_messageID']]))
                {
                    $reply_counter[$result['replyTo_messageID']] = 1;
                }
                else
                {
                    $reply_counter[$result['replyTo_messageID']] = $reply_counter[$result['replyTo_messageID']] + 1;
                }
            }
        }
        
        return $reply_counter;
    }
        
    public function set_message()
    {   	
                           $gcal = array();
                          $focus = $this->input->post('msg_concerns');
                $data['message'] = trim($this->input->post('message'));
        $data['userID_messages'] = $this->input->post('id');
            $gcal['event_start'] = $this->input->post('event_start');
              $gcal['event_end'] = $this->input->post('event_end');
                $gcal['address'] = trim($this->input->post('address'));
                $gcal['summary'] = trim($this->input->post('summary'));
            $gcal['description'] = $data['message'];
              $gcal['attendees'] = $this->input->post('attendees');
         
        if(isset($_POST['replyID'])){
            $data['replyTo_messageID'] = $this->input->post('replyID');
        }
        
         $success = $this->db->insert('messages', $data);
        $insertID = $this->db->insert_id();

        // insert into the message concerns table if needed
        if($focus !== FALSE) 
        {
            $data = array();
            $data['issueID_concerns'] = $focus;
            
         // insert the message "focus" into the message_concerns table
            $data['messageID_concerns'] = $insertID;
         
            $this->db->insert('message_concerns' , $data);
        }
        
        // call our custom helper function to insert the google calendar event if needed    
        $is_event = $this->input->post('calendar_event');
        if(!empty($is_event) || $is_event !== FALSE)
        {
                $event = new gcalendar_events;
             $cal_data = $event->setEvent($gcal);
             
             if(!is_array($cal_data))
             {
                 // couldn't successfully enter the event
                 unset($_POST['calendar_event']);
             }
             else
             {
                                      $data = array();
                  $data['messageID_events'] = $insertID;
                 $data['googlecalendar_id'] = $cal_data['id'];
                           $data['summary'] = $cal_data['summary'];
                     $data['calendar_date'] = $cal_data['calendar_datetime'];
             }
             
             $this->db->insert('events',$data);
        }
        
    }
    
    public function update_message($ID, $eventID = FALSE)
    { 
                           $gcal = array();
                          $focus = $this->input->post('msg_concerns');
                $data['message'] = trim($this->input->post('message'));
        $data['userID_messages'] = $this->input->post('id');
            $gcal['event_start'] = $this->input->post('event_start');
              $gcal['event_end'] = $this->input->post('event_end');
                $gcal['address'] = trim($this->input->post('address'));
                $gcal['summary'] = trim($this->input->post('summary'));
            $gcal['description'] = $data['message'];
              $gcal['attendees'] = $this->input->post('attendees');
        
        $success = $this->db->update('messages', $data, 'messageID = '.$ID );
        
        // update the message focus
        if($focus !== FALSE && !empty($focus)) 
        {
            // re-initialize the $data variable
                                   $data = array();
               $data['issueID_concerns'] = $focus;
                              
            // insert the message "focus" into the message_concerns table
               $this->db->where('messageID_concerns',$ID);
               $this->db->update('message_concerns' , $data);
        }
        
        // update the google calendar if needed
        $is_event = $this->input->post('calendar_event');
        
        if(isset($eventID) || !empty($is_event))
        {
            // call our google calendar library and update the calendar event
            // or insert one if it didn't already exist and the user wants to insert it
       
            $event = new gcalendar_events();
            if(isset($eventID))
            {
                $cal_data = $event->updateEvent($eventID,$gcal);
                $update = 1;
            }
            else
            {
                $cal_data = $event->setEvent($gcal);
                $set = 1;
            }
            
            // save the result of our set or update operation
            if(!is_array($cal_data))
            {
                 // couldn't successfully enter the event
                 unset($_POST['calendar_event']);
            }
            else
            {
                                      $data = array();
                  $data['messageID_events'] = $ID;
                 $data['googlecalendar_id'] = $cal_data['id'];
                           $data['summary'] = $cal_data['summary'];
                     $data['calendar_date'] = $cal_data['calendar_datetime'];
             }
             if(isset($update))
             {
                $this->db->where('messageID_events', $ID);
                $this->db->update('events',$data);
             }
             elseif(isset($set))
             {
                 $this->db->insert('events', $data);
             }
        }
    }
    
    private function get_eventID($ID)
    {
        $this->db->select('googlecalendar_id');
        $query = $this->db->get_where('events',array('messageID_events' => $ID) );
        
        return $query->row_array();
    }
    
    public function get_eventData($eventID,$use_obj = TRUE)
    {
        $event = new gcalendar_events;
        return $event->getEvent($eventID,$use_obj);
    }
    
    public function delete_message($ID,$userID)
    {
        // get the google calendar id
        $calID = $this->get_eventID($ID);
        
        // delete message and any replies along with it(in the future, consider disallowing deletion of messages with replies)
        $this->db->where('userID_messages = '.$userID.' AND messageID = '.$ID.' OR replyTo_messageID = '.$ID);
        $success = $this->db->delete('messages');
        
        // delete event from google calendar
        if($success && !empty($calID['googlecalendar_id']))
        {
            // if we get here, the current user must be authorized to have deleted that message
            $event = new gcalendar_events();
            $event->deleteEvent($calID['googlecalendar_id']);
        }
    }
}
