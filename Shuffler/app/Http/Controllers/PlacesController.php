<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlacesController extends Controller
{
    public function index(){
        $title ='Places';
        return view('places',compact('title'));

    }
}
