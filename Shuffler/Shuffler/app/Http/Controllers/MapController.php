<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mapper;

class MapController extends Controller
{
    public function index(){
        Mapper::map(23.815103, 90.423344);
    
        return view('dashboard');
    }
}
