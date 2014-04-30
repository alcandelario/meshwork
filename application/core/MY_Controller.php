<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Public_Controller extends CI_Controller {
        
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('auth');
        $this->load->helper('language');
        $this->load->database();
        
        if( $this->ion_auth->logged_in() ) 
        {
                                $the_user = $this->ion_auth->user()->row();
                  $this->data['the_user'] = $the_user->first_name.' '.$the_user->last_name;
                        $this->data['id'] = $the_user->id;
             @$this->data['profilephoto'] = $the_user->profilephoto_filepath;

            if($this->ion_auth->in_group("admin"))
            {
               $this->data['is_admin'] = TRUE;
            }

            if($this->ion_auth->in_group("superuser"))
            {
                $this->data['is_super'] = TRUE;
            }

            $this->load->vars($this->data);
        }
        else
        {
            $this->data['the_user'] = "Guest";
        }
    }
}

class Admin_Controller extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->lang->load('auth');
        $this->load->helper('language');
        $this->load->database();
        
        if( $this->ion_auth->logged_in() ) 
        {
                                $the_user = $this->ion_auth->user()->row();
                  $this->data['the_user'] = $the_user->first_name.' '.$the_user->last_name;
                        $this->data['id'] = $the_user->id;
             @$this->data['profilephoto'] = $the_user->profilephoto_filepath;

            if($this->ion_auth->in_group("admin"))
            {
               $this->data['is_admin'] = TRUE;
            }

            if($this->ion_auth->in_group("superuser"))
            {
                $this->data['is_super'] = TRUE;
            }

            $this->load->vars($this->data);
        }    

    }
}

class Common_Auth_Controller extends CI_Controller {
   //Class-wide variable to store user object in.
    protected $the_user;
     
    public function __construct() {

        parent::__construct();
        $this->lang->load('auth');
        $this->load->helper('language');
        $this->load->database();

        if( $this->ion_auth->logged_in() ) 
        {
                                    $the_user = $this->ion_auth->user()->row();
                      $this->data['the_user'] = $the_user->first_name.' '.$the_user->last_name;
                            $this->data['id'] = $the_user->id;
                 @$this->data['profilephoto'] = $the_user->profilephoto_filepath;

            if($this->ion_auth->in_group("admin"))
            {
               $this->data['is_admin'] = TRUE;
            }

            if($this->ion_auth->in_group("superuser"))
            {
                $this->data['is_super'] = TRUE;
            }

            $this->load->vars($this->data);
        }
        else 
        {
              redirect('login','refresh');
        }           
    }
}