@extends('layouts.app')

@section('content')
<body style="background-image:url('Background.PNG')">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div align='center' class="card-header">Welcome to</div>

                <div align='center' class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h1>Shuffler</h1> <br>
                </div>
            </div>

        </div>
        <div>
        <br><br><br>
        <div style="width: 1600px; height: 600px;">
            {!! Mapper::render() !!}
        </div>
    </div>
    </div>
</div>
@endsection
