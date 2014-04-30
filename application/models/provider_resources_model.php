<?php
class provider_resources_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
            $this->load->helper('url');
    }
}
