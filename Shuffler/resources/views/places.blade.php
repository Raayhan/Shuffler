@extends('layouts.app')
@section('content')
<body style="background-image:url('Background.PNG')">
    <div align='center'>
        <h1>{{$title}}</h1>
        <p style="font-size:200%;">You are at: {{$location}}</p>
        <p style="font-size:150%;">Found: {{count($places)}} places!</p>
        @if(count($places) > 1)
            @foreach($places as $place)
                <div align='center' class="card text-white bg-dark mb-3" style="max-width: 25rem;">
                    <div class="card-header">Place ID: <b>{{$place['place_id']}}</b></div>
                    <div class="card-body">
                        <h1 class="card-title">{{$place['name']}}</h1>
                        Type: <b>
                            @foreach(explode(';', $place['types']) as $type)
                                <u>{{$type}}</u>
                            @endforeach</b><br>
                                
                        Rating: <b>{{$place['rating']}}</b><br>
                    </div>
                    <h6 class="card-footer">Address: {{$place['vicinity']}}</h6>
                </div>
                <br>
            @endforeach
        @else
            <p>No places found.</p>
        @endif
    </div>
</body>
@endsection