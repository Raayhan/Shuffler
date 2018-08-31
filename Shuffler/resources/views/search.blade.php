@extends('layouts.app')
@section('content')
    @include('inc.placesnav')
    
    <div align='center'>
        <h1>{{$title}}</h1>

        <p style="font-size:150%;">You are at: {{$location}}</p>
        
        <form action="search" method="post">
            <p>
            <span style="white-space:nowrap">
                <label for="selectedType" style="font-size: 120%; display: inline-block;">Type: &nbsp;</label>
                <select class="form-control" name="selectedType" id="" style="max-width: 300px; display: inline-block;">
                    <option value=""></option>
                    @foreach($availableTypes as $availableType)
                    <option value={{$availableType}}>{{$availableType}}</option>
                    @endforeach
                </select>
                <br>(keep empty to find all)
            </span>
            </p>

            <p>
            <label for="selectedRadius" style="font-size: 120%;">Radius: &nbsp;</label>
            @foreach($availableRadii as $availableRadius)
                @if($availableRadius == 500)
                    <label class="radio-inline">
                        <input type="radio" name="selectedRadius" value={{$availableRadius}} checked>{{$availableRadius}}&nbsp;
                    </label>
                @else
                    <label class="radio-inline">
                        <input type="radio" name="selectedRadius" value={{$availableRadius}}>{{$availableRadius}}&nbsp;
                    </label>
                @endif
            @endforeach
            </p>

            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="submit" name="submit" value="SEARCH" type="button" class="btn btn-primary" 
            style="background-color:#2f4f4f; font-size: 100%; height: 50px; width: 100px">

        </form>

    </div>
@endsection