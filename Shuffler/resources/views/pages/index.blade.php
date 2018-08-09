@extends('layouts.app')
@section('content')
<body style="background-image:url('Background.PNG'); background-repeat: no-repeat; background-size:cover">
<br><br><br><br><br><br><br><br>
    <div align='center'>
    <div align='center' class="card text-white bg-dark mb-3" style="max-width: 25rem;">
        <div class="card-header">Welcome To</div>
        <div class="card-body">
          <h1 class="card-title">Shuffler</h1>
          <br>
          <p><a class="btn-danger btn-lg"href="/login"role="button">Login</a> <a class="btn-success btn-lg" href="/register" role="button">Register</a></p>
          <br><br>
        
        </div>
        <h6 class="card-footer">Please Login or Register to use the services</h6>
    </div>
    </div>

@endsection
