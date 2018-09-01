@extends('layouts.app')
@section('content')
    <div align='center'>
        <br>
        <h1><span style="color: slategrey">{{$title}}</span></h1>
        <h2>Shuffler is a passion project</h2>
        <h4>built to provide an ease of choice when looking for places to go.</h4><br>
        <h4>Mostly because of scenarios like this:</h4>
        <img src="faux_chat.png">
        <br>
    </div>
    <div align='center'>
        <ul style="list-style-type: none; margin: 0; padding: 0; overflow: hidden; 
        background-color: #333; position: fixed; left: 0vw; bottom: 0; width: 100vw; z-index:1;
        display: flex; justify-content:center;">
            <li style="float: center;">
                <a style="display: block; color: white; text-align: center; padding: 10px;">
                Contact us at:</a>
            </li>
            <li style="float: center;">
                <a style="display: block; color: lightblue; text-align: center; padding: 10px;"
                href="mailto:shuffler.dev@gmail.com?Subject=Hello">
                    shuffler.dev@gmail.com</a>
            </li>
        </ul>
    </div>
@endsection