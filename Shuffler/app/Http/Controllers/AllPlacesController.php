<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Search;
use App\Place;
use App\History;
use DB;
use Auth;
use Socialite;
use GooglePlaces;
use Location;

class AllPlacesController extends Controller
{
    private $title = 'Searches';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = $this->title;
        if($this->findLastSearch()){
            $searched = $this->findLastSearch();
            $location = $searched->location;
            $type = $searched->type;
            $radius = $searched->radius;
        }
        else{
            $location = $this->getUserLocation();
            $type = '';
            $radius = 500;
        }
        $places = $this->findPlaces($location, $radius, $type);
        return view('allplaces', compact([
            $title => 'title', $location => 'location',
            $type => 'type', $radius => 'radius',]))
            ->with('places', $places);
    }

    /**
     * Wrapper function for finding places.
     * 
     * @param string $location
     * @param decimal $radius
     * @param string[] $type
     * @param array $places
     * @return GooglePlaces
     */
    public function findPlaces($location, $radius, $type){
        if($this->findHistory($location, $radius, $type)){
            $places = $this->getPlaces($location, $radius, $type);
        }
        else{
            $places = $this->searchPlaces($location, $radius, $type);
            $places = $this->formatPlaces($places);
            $this->storePlaces($places);
            $this->storeHistory($location, $radius, $type, $places);
        }
        return $places;
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
     * Find pre-existing parameters in database.
     * 
     * @param string $location
     * @param decimal $radius
     * @param string[] $type
     * @return boolean
     */
    public function findLastSearch(){
        return (Search::where('user_id', '=', Auth::user()->id)
        ->orderBy('id', 'desc')->first());
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
     * Get locaiton from user IP.
     * 
     * @return Location
     */
    public function getUserLocation(){
        
        $ipTracker = file_get_contents('http://checkip.dyndns.com/');
        preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $ipTracker, $ipFinder);
        $ip = $ipFinder[1];
        // $ip = Request::getClientIp();
        $user_location = Location::get($ip); // Replace with IP
        $location = $user_location->latitude.', '.$user_location->longitude;
        return $location;
    }
}
