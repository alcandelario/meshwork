<?php
class resources_list_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
            $this->load->helper('url');
    }
}

