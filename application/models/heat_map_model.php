<?php
class heat_map_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
            $this->load->helper('url');
    }
}
