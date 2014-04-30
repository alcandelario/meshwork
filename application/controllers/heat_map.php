<?php
class heat_map extends Common_Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('assets_model');
                $this->load->helper('url'); 
	}

	public function index()
	{
//		$data['datapoints'] = $this->assets_model->get_asset();
                $data['title'] = 'Declare Home';
                
                $address = "1000 W. 95th St, Chicago, IL, 60602";
                $latLng = $this->lookup($address);
                $locationString = "['Home','".$latLng['longitude']."','".$latLng['latitude']."','".$address."','"."T']";
                $data['locationsArray'] = $locationString;
                $data['centerlng'] = $latLng['latitude'];
                $data['centerlat'] = $latLng['longitude'];
                $this->load->view('templates/header', $data);
                $this->load->view('pages/home', $data);
                $this->load->view('templates/footer');
	}

	public function view($address)
	{
            $latLng = $this->lookup($address);
            if (empty($data['news_item']))
            {
                show_404();
            }

            $data['title'] = $data['news_item']['title'];

            $this->load->view('templates/header', $data);
            $this->load->view('news/view', $data);
            $this->load->view('templates/footer');
	}
        
        function lookup($string){
 
            $string = str_replace (" ", "+", urlencode($string));
            $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $details_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);

            // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
            if ($response['status'] != 'OK') {
             return null;
            }

            //print_r($response);
            $geometry = $response['results'][0]['geometry'];

             $longitude = $geometry['location']['lat'];
             $latitude = $geometry['location']['lng'];

             $array = array(
                 'latitude' => $geometry['location']['lng'],
                 'longitude' => $geometry['location']['lat'],
                 'location_type' => $geometry['location_type'],
             );

             return $array;
        }
        
}
?>

