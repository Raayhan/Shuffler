<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 180);

use Illuminate\Http\Request;
use GooglePlaces;
use App\Place;
use App\History;
use DB;
use Auth;
use Socialite;

class PlacesController extends Controller
{
    // North South University Coordinates: '23.815103, 90.423344'
    // Bangladesh National Museum: '23.7374803, 90.3922557'
    // Malibagh Bus Stop: '23.7500974, 90.4108474'
    // Airport Bus Stand: '23.851778,90.4050458'
    // 300 feet: '23.773902, 90.3847508'

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the places.
     *
     * @param string $location
     * @param decimal $radius
     * @param string[] $params
     * @return GooglePlaces
     */
    public function index($location = '23.773902, 90.3847508', $radius = 1000, $type = 'restaurant')
    {
        $title = 'Places';
        $places;
        if($this->findHistory($location, $radius, $type)){
            $places = $this->getPlaces($location, $radius, $type);
        }
        else{
            $places = $this->searchPlaces($location, $radius, $type);
            $places = $this->formatPlaces($places);
            $this->storePlaces($places);
            $this->storeHistory($location, $radius, $type, $places);
        }
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
    public function searchPlaces($location, $radius, $type){
        // sleep(30);
        $params['type'] = $type;
        $places = GooglePlaces::nearbySearch($location, $radius, $params);
        $results = $places['results'];
        // if(empty($places['next_page_token']) === false) {
        //     $params['pagetoken'] = $places['next_page_token'];
        //     return $results->merge($this->search($location, $radius, $params));
        // }
        return $results;
    }

    /**
     * Formats places found in a suitable way for viewing/saving.
     * 
     * @param array $places
     * @return array $results
     */
    public function formatPlaces($places){
        $results = collect();
        foreach($places as $place){
            $coordinates = $place['geometry']['location']['lat'].', '.$place['geometry']['location']['lng'];
            $place['coordinates'] = $coordinates;
            $types = $place['types'];
            $place['types'] = '';
            foreach($types as $type){
                if($type === 'point_of_interest') continue;
                $place['types'] = $place['types'].$type.';';
            }
            if(!(isset($place['rating']))){
                $place['rating'] = 0;
            }
            $place['rating'] = number_format((float)$place['rating'], 2, '.', '');
            
            $result = $place;
            $results->push($place);
        }
        return $results;
    }
    
    /**
     * Find places in a radius around a location.
     * 
     * @param string $location
     * @param decimal $radius
     * @param string[] $type
     * @return void
     */
    public function storePlaces($places){
        foreach($places as $place){
            if(Place::where('place_id', '=', $place['place_id'])->first()) continue;
            $toStore = new Place;
            $toStore->place_id = $place['place_id'];
            $toStore->coordinates = $place['coordinates'];
            $toStore->name = $place['name'];
            $toStore->vicinity = $place['vicinity'];
            $toStore->types = $place['types'];
            $toStore->rating = $place['rating'];
            $toStore->save();
        }
    }

    /**
     * Get places from the database.
     * 
     * @param string $location
     * @param decimal $radius
     * @param string[] $type
     * @return GooglePlaces
     */
    public function getPlaces($location, $radius, $type){
        $histories = History::where([
            ['location', '=', $location], 
            ['radius', '=', $radius], 
            ['type', '=', $type]
            ])->get();
        $results = collect();
        foreach($histories as $history){
            $place = Place::where('place_id', '=', $history['place_id'])->first();
            $results->push($place->toArray());
        }
        return $results;
    }

    /**
     * Find pre-existing parameters in database.
     * 
     * @param string $location
     * @param decimal $radius
     * @param string[] $type
     * @return boolean
     */
    public function findHistory($location, $radius, $type){
        if(History::where([
            ['location', '=', $location], 
            ['radius', '=', $radius], 
            ['type', '=', $type]
            ])->first()) return true;
        else return false;
    }

    /**
     * Store search parameter as well as result IDs.
     * 
     * @param string $location
     * @param decimal $radius
     * @param string[] $type
     * @param array $places
     * @return GooglePlaces
     */
    public function storeHistory($location, $radius, $type, $places){
        foreach($places as $place){
            // if(Place::where('place_id', '=', $place['place_id'])->first()) continue;
            $toStore = new History;
            $toStore->location = $location;
            $toStore->radius = $radius;
            $toStore->type = $type;
            $toStore->user_id = Auth::user()->id;
            $toStore->place_id = $place['place_id'];
            $toStore->save();
        }
    }
}
