<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 180);

use Illuminate\Http\Request;
use GooglePlaces;
use App\Place;
use DB;

class PlacesController extends Controller
{
    // North South University Coordinates: '23.815103, 90.423344'
    // Bangladesh National Museum: '23.7374803, 90.3922557'


    /**
     * Display a listing of the places.
     *
     * @param string $location
     * @param decimal $radius
     * @param string[] $params
     * @return GooglePlaces
     */
    public function index($location = '23.7374803, 90.3922557', $radius = 1000, $type = 'restaurant')
    {
        $title = 'Places';
        $params['type'] = $type;
        $places = $this->searchPlaces($location, $radius, $params);
        $this->storePlaces($places);
        return view('places',compact('title'))->with('places', $places);
    }

    /**
     * Find places in a radius around a location.
     * 
     * @param string $location
     * @param decimal $radius
     * @param string[] $type
     * @return GooglePlaces
     */
    public function searchPlaces($location, $radius, $params){
        // sleep(30);
        $places = GooglePlaces::nearbySearch($location, $radius, $params);
        $results = $places['results'];
        // if(empty($places['next_page_token']) === false) {
        //     $params['pagetoken'] = $places['next_page_token'];
        //     return $results->merge($this->search($location, $radius, $params));
        // }
        return $results;
    }

    public function storePlaces($places){
        foreach($places as $place){
            if(Place::where('place_id', '=', $place['place_id'])->first()) continue;
            $toStore = new Place;
            $toStore->place_id = $place['place_id'];
            $toStore->coordinates = $place['geometry']['location']['lat'].', '.$place['geometry']['location']['lng'];
            $toStore->name = $place['name'];
            $toStore->vicinity = $place['vicinity'];
            $toStore->types = '';
            foreach($place['types'] as $type){
                if($type === 'point_of_interest') continue;
                $toStore->types = $toStore->types.$type.';';
            }
            $toStore->rating = 0;
            if(isset($place['rating'])) $toStore->rating = $place['rating'];
            $toStore->save();
        }
    }

    public function findHistory($location, $radius, $type){
        
    }

    public function storeHistory($location, $radius, $type){

    }
}
