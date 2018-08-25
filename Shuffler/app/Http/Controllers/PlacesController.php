<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 180);

use Illuminate\Http\Request;
use GooglePlaces;

class PlacesController extends Controller
{
    // North South University Coordinates: '23.815103, 90.423344'
    // Bangladesh National Museum: '23.7374803, 90.3922557'

    public function index($location = '23.7374803, 90.3922557', $radius = 1000, $params = []){
        $title = 'Places';
        $type = 'restaurant';
        $params['type'] = $type;
        $places = $this->search($location, $radius, $params);
        return view('places',compact('title'))->with('places', $places);
    }

    public function search($location, $radius, $params){
        sleep(30);
        $places = GooglePlaces::nearbySearch($location, $radius, $params);
        $results = $places['results'];
        if(empty($places['next_page_token']) === false) {
            $params['pagetoken'] = $places['next_page_token'];
            return $results->merge($this->search($location, $radius, $params));
        }
        return $results;
    }
}
