<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class utils {
    
    public function __construct()
    {
       
    }

    function _get_csrf_nonce()
    {
            $this->load->helper('string');
            $key   = random_string('alnum', 8);
            $value = random_string('alnum', 20);
            $this->session->set_flashdata('csrfkey', $key);
            $this->session->set_flashdata('csrfvalue', $value);

            return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
            if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey'))  == $this->session->flashdata('csrfvalue'))
            {
                    return TRUE;
            }
            else
            {
                    return FALSE;
            }
    }
}
