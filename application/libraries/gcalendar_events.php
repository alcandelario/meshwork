<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class gcalendar_events {
    
    public function __construct()
    {
          
       require_once ROOTDIR.'application'.dir_separator.'third_party'.dir_separator.'google-api-php-client'.dir_separator.'src'.dir_separator.'Google_Client.php';
       require_once ROOTDIR.'application'.dir_separator.'third_party'.dir_separator.'google-api-php-client'.dir_separator.'src'.dir_separator.'contrib'.dir_separator.'Google_CalendarService.php';
       @session_start();
       
       // this function creates/saves the $this->client and $this->cal objects
       $this->login();
    
       $this->calendarID = 'resilience.chicago@gmail.com';
       $this->eventURL = "https://www.google.com/calendar/event?eid=";
       
    }
    
    public function getEventList($use_obj = NULL,$num_results = '5',$cal_ID = NULL)
    {
        if(isset($use_obj))
        {
            $this->client->setUseObjects($use_obj);
        }
        // return the default number of results or whatever the user passed in
        
        /****** TEMPORARY****** MUST PASS $cal_ID in dynamically *********/
        if(PILSEN_CAL === TRUE)
        {
            $cal_ID = 'ngt5nedpe110254snaahn1davg@group.calendar.google.com';
        }
        else 
        {
            $cal_ID = $this->calendarID;
        }
        
        return $this->cal->events->listEvents($cal_ID,array('orderBy' => "startTime",'singleEvents' => 'TRUE','maxResults' => $num_results,"timeMin" => date(DateTime::ATOM) ));
    }
    
    public function deleteEvent($eventID)
    {
        /****** TEMPORARY****** MUST PASS $cal_ID in dynamically *********/
        if(PILSEN_CAL === TRUE)
        {
            $cal_ID = 'ngt5nedpe110254snaahn1davg@group.calendar.google.com';
        }
        else 
        {
            $cal_ID = $this->calendarID;
        }
        $this->cal->events->delete($cal_ID,$eventID);
    }

    public function getEvent($eventID,$use_obj = NULL)
    {
        if(isset($use_obj))
        {
            $this->client->setUseObjects($use_obj);
        }
        
        /****** TEMPORARY****** MUST PASS $cal_ID in dynamically *********/
        if(PILSEN_CAL === TRUE)
        {
            $cal_ID = 'ngt5nedpe110254snaahn1davg@group.calendar.google.com';
        }
        else 
        {
            $cal_ID = $this->calendarID;
        }
        return $this->cal->events->get($cal_ID,$eventID);
        
    }
    
    public function updateEvent($eventID,$gcal)
    {
        /****** TEMPORARY****** MUST PASS $cal_ID in dynamically *********/
        if(PILSEN_CAL === TRUE)
        {
            $cal_ID = 'ngt5nedpe110254snaahn1davg@group.calendar.google.com';
        }
        else 
        {
            $cal_ID = $this->calendarID;
        }
        
        
        $start_DT = strtotime($gcal['event_start']);
          $end_DT = strtotime($gcal['event_end']);

          $startD = date('Y-m-d\TG:i:00.000O',$start_DT);
            $endD = date('Y-m-d\TG:i:00.000O',$end_DT);
       
       // don't do anything unless we have an actual date/time set       
        if(
            !empty($start_DT)  ||
            !empty($end_DT)    
          )
        {
            // grab our relevant calendar data
                $address = $gcal['address'];
                $summary = $gcal['summary'];
            $description = $gcal['description'];
              $attendees = $gcal['attendees'];
                         
            // let's use objects here to use the built in "set" methods
            $this->client->setUseObjects(TRUE);           
            
            // create new google calendar event object
            $event = $this->cal->events->get($cal_ID,$eventID);
          
            $event->setSummary($summary);
            $event->setDescription($description);
            $event->setLocation($address);
            
            // set the timezone, though we shouldn't have to I think
            $tz = date('P');
            
            // create a start and endtime dateTime object
            $start = new Google_EventDateTime();
            $start->setTimeZone($tz);
            $start->setDateTime($startD);
            $event->setStart($start);
            
            $end = new Google_EventDateTime();
            $end->setTimeZone($tz);
            $end->setDateTime($endD);
            $event->setEnd($end);
            
            // create an attendees object if needed
            if(is_array($attendees) && count($attendees) > 0)
            {
                $guests = array();
                foreach($attendees as $key => $attendee)
                {
                    $details = explode(',', $attendee);
                    $guests[]=array('displayName' => $details[1], 'email' => $details[0]);
                }
                $event->setAttendees($guests);
            }
            
         $updatedEvent = $this->cal->events->update($cal_ID,$eventID,$event);
         
         if(is_object($updatedEvent))
            {
               // return data to be stored in the dB
                $time = date('Y-m-d G:i:00',$start_DT);
                return array('id' => $updatedEvent->getID() ,'calendar_datetime' => $time,'summary' => $updatedEvent->getSummary());
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    public function setEvent($gcal)
    {
        /****** TEMPORARY****** MUST PASS $cal_ID in dynamically *********/
        if(PILSEN_CAL === TRUE)
        {
            $cal_ID = 'ngt5nedpe110254snaahn1davg@group.calendar.google.com';
        }
        else 
        {
            $cal_ID = $this->calendarID;
        }
        
        
        $start_DT = strtotime($gcal['event_start']);
          $end_DT = strtotime($gcal['event_end']);

          $startD = date('Y-m-d\TG:i:00.000O',$start_DT);
            $endD = date('Y-m-d\TG:i:00.000O',$end_DT);
       
       // don't do anything unless we have an actual date/time set       
        if(
            !empty($start_DT)  ||
            !empty($end_DT)    
          )
        {
            // grab our relevant calendar data
                $address = $gcal['address'];
                $summary = $gcal['summary'];
            $description = $gcal['description'];
              $attendees = $gcal['attendees'];
            
            //  create new google calendar event object
            $event = new Google_Event();
            $event->setSummary($summary);
            $event->setDescription($description);
            $event->setLocation($address);
           
            // set the timezone, though we shouldn't have to I think
            $tz = date('P');
            
            // create a start and endtime dateTime object
            $start = new Google_EventDateTime();
            $start->setTimeZone($tz);
            $start->setDateTime($startD);
            $event->setStart($start);
            
            $end = new Google_EventDateTime();
            $end->setTimeZone($tz);
            $end->setDateTime($endD);
            $event->setEnd($end);
            
            // create an attendees object
            if(is_array($attendees) && count($attendees) > 0)
            {
                $guests = array();
                foreach($attendees as $key => $attendee)
                {
                    $details = explode(',', $attendee);
                    $guests[]=array('displayName' => $details[1], 'email' => $details[0]);
                }
                $event->setAttendees($guests);
            }
            
            // insert the event into the google calendar and email all attendees
            $createdEvent = $this->cal->events->insert($cal_ID,$event,array('sendNotifications' => TRUE));
        
            // save the ecvent ID
            $id = $createdEvent->getID();
                  
            if(isset($id) && $id !== FALSE)
            {
                // return data to be stored in the dB
                $time = date('Y-m-d G:i:00',$start_DT);
                return array('id' => $id ,'calendar_datetime' => $time,'summary' => $createdEvent->getSummary());
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    private function login()
    {
        // trying to use service account feature
        $clientID = '957459993212-b41qedc8mm8bcftbr34p8r9bm57s1mda.apps.googleusercontent.com';
        $acctName = '957459993212-b41qedc8mm8bcftbr34p8r9bm57s1mda@developer.gserviceaccount.com';
          $cal_ID = 'resilience.chicago@gmail.com';
             $key = ROOTDIR.dir_separator."system".dir_separator.'Google_Service_Account'.dir_separator.'87a531ba5903a643343bfdac58732af8bfd4ae6d-privatekey.p12';
           
          $this->client = new Google_Client();
          $this->client->setApplicationName("Google Calendar PHP Starter Application");
          $this->client->setUseObjects(TRUE);

        // set the token new each time but consider implementing database storage as it's recommended
        if(isset($_SESSION['token']))
        {
            $this->client->setAccessToken($_SESSION['token']);
        }

        // get key contents
        $key = file_get_contents($key);

        $this->client->setAssertionCredentials(
            new Google_AssertionCredentials($acctName, 
                 array('https://www.googleapis.com/auth/calendar'),
                 $key
            )
         );

        if(isset($_SESSION['token']))
        {
            $this->client->setAccessToken($_SESSION['token']);
        }
        // save the calendar service object
        $this->cal = new Google_CalendarService($this->client);
  
    } 
}


