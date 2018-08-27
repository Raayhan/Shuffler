@extends('layouts.app')
@section('content')
<body style="background-image:url('Background.PNG')">
    <h1>{{$title}}</h1>
    <p>This is the places page.</p>
    No. of results: {{count($places)}}<br><br>
    @if(count($places) > 1)
        @foreach($places as $place)
            Place ID: {{$place['place_id']}}<br>
            Name: {{$place['name']}}<br>
            Coordinates: {{$place['coordinates']}}<br>
            Vicinity: {{$place['vicinity']}}<br>
            Type: {{$place['types']}}<br>
            Rating: {{$place['rating']}}<br>
            <br>
        @endforeach
    @else
        <p>No places found.</p>
    @endif
</body>
@endsection