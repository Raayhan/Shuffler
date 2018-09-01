@extends('layouts.app')
@section('content')
    @include('inc.placesnav')
    
    <div align='center'>
        <h1>{{$title}}</h1>

        <p style="font-size:150%;">You are at: {{$location}}</p>
        <p style="font-size:120%;"><b>Current:</b> Type - <b><u>
            @if($type == '')All @else{{$type}} @endif</u></b>
        &nbsp; Radius - <b><u>{{$radius}}</u></b></p>

        <form action="shuffle" method="get">
            <input type="image" src="{{ asset('shuffle.PNG') }}" 
            style= "max-width: 150px; max-height: 150px; margin: 0;
            z-index:1; float: center; justify-content:center;">
        </form>

        @if(isset($places))
            <div align='center' class="card text-white bg-dark mb-3" style="max-width: 25rem;">
                <div class="card-header">Place ID: <b>{{$places['place_id']}}</b></div>
                <div class="card-body">
                    <h1 class="card-title">{{$places['name']}}</h1>
                    Type: <b>
                        @foreach(explode(';', $places['types']) as $type)
                            <u>{{$type}}</u>
                        @endforeach</b><br>
                            
                    Rating: <b>{{$places['rating']}}</b><br>
                </div>
                <h6 class="card-footer">Address: {{$places['vicinity']}}</h6>
            </div>
        @else
            <p>No places found.</p>
        @endif




    </div>
@endsection