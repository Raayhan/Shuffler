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
use Location;

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
    public function index($location = '23.7374803, 90.3922557', $radius = 1000, $type = 'bank')
    {
        $title = 'Places';
        $user_location = $this->getUserLocation();
        $location = $user_location->latitude.', '.$user_location->longitude;
        $availableTypes = $this->getAvailableTypes();
        $availableRadii = $this->getAvailableRadii();
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
        return view('places',compact([$title => 'title', $location => 'location']))
        ->with('places', $places)
        ->with('availableTypes', $availableTypes)
        ->with('availableRadii', $availableRadii);
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
        
        $params['type'] = $type;
        $places = GooglePlaces::nearbySearch($location, $radius, $params);
        $results = $places['results'];
        // if(empty($places['next_page_token']) === false) {
        //     sleep(30);
        //     $params['pagetoken'] = $places['next_page_token'];
        //     return $results->merge($this->search($location, $radius, $params));
        // }
        return $results;
    }

    /**
     * Get locaiton from user IP.
     * 
     * @return Location
     */
    public function getUserLocation(){
        
        $ipTracker = file_get_contents('http://checkip.dyndns.com/');
        preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $ipTracker, $ipFinder);
        $ip = $ipFinder[1];
        // $ip = Request::getClientIp();
        $location = Location::get($ip); // Replace with IP
        return $location;
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
            $toStore = new History;
            $toStore->location = $location;
            $toStore->radius = $radius;
            $toStore->type = $type;
            $toStore->user_id = Auth::user()->id;
            $toStore->place_id = $place['place_id'];
            $toStore->save();
        }
    }

    
    /**
     * Return all available types to choose for places.
     * 
     * @return array $results
     */
    public function getAvailableTypes(){
        $availableTypes = [
            'accounting', 'airport', 'amusement_park', 'aquarium', 'art_gallery', 'atm', 
            'bakery', 'bank', 'bar', 'beauty_salon', 'bicycle_store', 'book_store', 
            'bowling_alley', 'bus_station', 
            'cafe', 'campground', 'car_dealer', 'car_rental', 'car_repair', 'car_wash', 
            'casino', 'cemetery', 'church', 'city_hall',
            'clothing_store', 'convenience_store', 'courthouse', 
            'dentist', 'department_store',
            'doctor', 'electrician', 'electronics_store', 'embassy', 'fire_station', 'florist',
            'funeral_home', 'furniture_store',
            'gas_station', 'gym',
            'hair_care', 'hardware_store', 'hindu_temple', 'home_goods_store', 'hospital',
            'insurance_agency',
            'jewelry_store',
            'laundry', 'lawyer',
            'library', 'liquor_store', 'local_government_office', 'locksmith', 'lodging',
            'meal_delivery', 'meal_takeaway', 'mosque', 'movie_rental', 'movie_theater', 
            'moving_company', 'museum',
            'night_club',
            'painter', 'park', 'parking', 'pet_store', 'pharmacy', 'physiotherapist',
            'plumber', 'police', 'post_office',
            'real_estate_agency', 'restaurant', 'roofing_contractor', 'rv_park',
            'school', 'shoe_store', 'shopping_mall', 'spa', 'stadium', 'storage', 'store',
            'subway_station', 'supermarket', 'synagogue',
            'taxi_stand', 'train_station', 'transit_station', 'travel_agency',
            'veterinary_care',
            'zoo',
        ];
        $results = array();
        foreach($availableTypes as $availableType){
            $result = str_replace('_', ' ', ucfirst($availableType));
            array_push($results, $result);
        }
        return $results;
    }

    /**
     * Return all available radii to choose for places.
     * 
     * @return array $results
     */
    public function getAvailableRadii(){
        $availableRadii = [
            500, 1000, 1500, 2000, 
        ];
        return $availableRadii;
    }

}
