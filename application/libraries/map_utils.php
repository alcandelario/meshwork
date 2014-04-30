<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class map_utils {
    
    public function __construct()
    {
       
    }

    public function geocode_address($provider,$type = 'org')
    {
        if(!empty( $provider['street_ofc']) && !empty($provider['city_ofc']) && !empty($provider['state_ofc']))
        {
            $address = $provider['street_ofc'].' '.$provider['city_ofc'].' '.$provider['state_ofc'].' '.@$provider['zipcode_ofc'];
            $address = str_replace (" ", "+", urlencode($address));
            $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $details_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);

            // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
            if ($response['status'] != 'OK') 
            {
                return null;
            }

            $geometry = $response['results'][0]['geometry'];

            $longitude = $geometry['location']['lat'];
            $latitude = $geometry['location']['lng'];
            
            if($type == 'org')
            {
                $lat = 'latitude_ofc';
                $lon = 'longitude_ofc';
            }
            elseif($type == 'heat')
            {
                $lat = 'lat_heat';
                $lon = 'long_heat';
            }
            elseif($type == 'user')
            {
                $lat = 'lat_user';
                $lon = 'lon_user';
            }
            else
            {
                $lat = 'latitude';
                $lon = 'longitude';
            }
            
             $provider[$lat] = $latitude;
             $provider[$lon] = $longitude;
        
        return $provider;
       }
       else
       {
           return $provider;
       }
    }
}