<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShuffleController extends AllPlacesController
{
    private $title = 'Shuffle';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = $this->title;
        if(parent::findLastSearch()){
            $searched = parent::findLastSearch();
            $location = $searched->location;
            $type = $searched->type;
            $radius = $searched->radius;
        }
        else{
            $location = parent::getUserLocation();
            $type = '';
            $radius = 500;
        }
        $places = parent::findPlaces($location, $radius, $type);
        $places = $this->shufflePlaces($places);
        return view('shuffle', compact([
            $title => 'title', $location => 'location',
            $type => 'type', $radius => 'radius',]))
            ->with('places', $places);
    }

    /**
     * Shuffle a list of places.
     *
     * @return GooglePlaces
     */
    public function shufflePlaces($places)
    {
        // if (!is_array($places)) return $places; 
        
        // $keys = array_keys($places); 
        // shuffle($keys); 
        // $random = array(); 
        // foreach ($keys as $key) { 
        //     $random[$key] = $places[$key]; 
        // }
        // return $random; 

        $keys = $places->keys()->toArray();
        $key = array_rand($keys);

        return $places[$key];
    }


}
