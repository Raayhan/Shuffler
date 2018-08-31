@extends('layouts.app')
@section('content')
<body style="background-image:url('Background.PNG')">
    <div align='center'>
        <ul style="list-style-type: none; margin: 0; padding: 0; overflow: hidden; 
        background-color: #333; position: fixed; left: 0vw; bottom: 0; width: 100vw; z-index:1;
        display: flex; justify-content:center;">
            <li style="float: center; border-right:1px solid #bbb;">
                <a style="display: block; color: white; text-align: 
                center; padding: 14px 16px; text-decoration: none;" 
                class="active" href="#home">
                <button type="button" class="btn btn-primary" 
                style="background-color:#2f4f4f; font-size: 60%;">
                SEARCH</button>
                </a>
                </li>
            <li style="float: center; border-right:1px solid #bbb;">
                <a style="display: block; color: white; text-align: center; 
                padding: 14px 16px; text-decoration: none;"
                href="#news">Shuffle</a></li>
            <li style="float: center; border-right:1px solid #bbb;">
                <a style="display: block; color: white; text-align: center; 
                padding: 14px 16px; text-decoration: none;"
                href="#contact">All</a></li>
            <li style="float: center;">
                <a style="display: block; color: white; text-align: center; 
                padding: 14px 16px; text-decoration: none;"
                href="#about">Recommended</a></li>
        </ul>
    </div>

    <div align='center'>
        <br><br>
        <h1>{{$title}}</h1>
            
        <p style="font-size:150%;">You are at: {{$location}}</p>

        <div class="form-group">
            <span style="white-space:nowrap">
                <label for="sel1" style="font-size: 120%; display: inline-block;">Type:&nbsp;&nbsp;</label>
                <select class="form-control" id="sel1" style="max-width: 300px; display: inline-block;">
                    <option></option>
                    @foreach($availableTypes as $availableType)
                        <option>{{$availableType}}</option>
                    @endforeach
                </select>
                    <br>(keep empty to find all)
            </span>
        </div> 

        <p>
        <label style="font-size: 120%;">Radius:&nbsp;&nbsp;</label>
        @foreach($availableRadii as $availableRadius)
            @if($availableRadius == 500)
                <label class="radio-inline"><input type="radio" name="optradio" checked>{{$availableRadius}}&nbsp;</label>
            @else
                <label class="radio-inline"><input type="radio" name="optradio">{{$availableRadius}}&nbsp;</label>
            @endif
        @endforeach
        </p>

        <p><button type="button" class="btn btn-primary" 
            style="background-color:#2f4f4f; font-size: 100%; height: 50px; width: 100px">
            SEARCH
        </button></p>
        <input type="image" src="{{ asset('shuffle.PNG') }}" style= "max-width: 100px; max-height: 100px">

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