@extends('layouts.app')
@section('content')
<body style="background-image:url('Background.PNG')">
    <h1>{{$title}}</h1>
    <p>This is the places page.</p>
    No. of results: {{count($places)}}<br><br>
    @if(count($places) > 1)
        @foreach($places as $place)
            Name: {{$place['name']}}<br>
            Vicinity: {{$place['vicinity']}}<br>
            Type: 
            @foreach($place['types'] as $type)
                @if($type == 'point_of_interest') @continue @endif
                {{$type}};
            @endforeach
            <br><br>
        @endforeach
    @else
        <p>No places found.</p>
    @endif
</body>
@endsection