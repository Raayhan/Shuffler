<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Search;
use DB;
use Auth;
use Location;

class SearchesController extends Controller
{
    private $location;
    private $radius;
    private $type;
    private $title = 'Searches';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->location = $this->getUserLocation();
        $this->radius = 500;
        $this->type = '';
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = $this->title;
        $location = $this->location;
        $radius = $this->radius;
        $type = $this->type;
        $availableTypes = $this->getAvailableTypes();
        $availableRadii = $this->getAvailableRadii();
        return view('search',compact([
            $title => 'title', 
            $location => 'location', 
            $radius => 'radius', 
            $type => 'type']))
        ->with('availableTypes', $availableTypes)
        ->with('availableRadii', $availableRadii);
    }

    public function findSearch($location, $radius, $type){
        if(History::where([
            ['location', '=', $location], 
            ['radius', '=', $radius], 
            ['type', '=', $type]
            ])->first()) return true;
        else return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->type = ($request->input('selectedType'));
        $this->radius = ($request->input('selectedRadius'));
        $toStore = new Search;
        $toStore->location = $this->location;
        $toStore->radius = $this->radius;
        $toStore->type = $this->type;
        $toStore->user_id = Auth::user()->id;
        $toStore->save();
        return $this->show();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        $location = $this->location;
        $radius = $this->radius;
        $type = $this->type;
        return redirect('/allplaces');
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

    /**
     * Return all available types to choose for places.
     * 
     * @return array $results
     */
    public function getAvailableTypes()
    {
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
    public function getAvailableRadii()
    {
        $availableRadii = [
            500, 1000, 1500, 2000, 
        ];
        return $availableRadii;
    }
}
