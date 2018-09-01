<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class PagesController extends Controller
{
    public function index(){
        if(Auth::check()){
            return redirect('/search');
        }
        else{
            $title = 'Welcome to Shuffler';
            return view('pages.index',compact('title'));
        }
    }

    public function about(){
        $title = 'About Us';
        return view('pages.about',compact('title'));
    }
}
