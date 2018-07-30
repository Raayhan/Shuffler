@extends('layouts.app')
@section('content')
<body style="background-image:url('Background.png')">
<!--Jumbotron-->



<div class="row mb-5">
  <div class="col-md-12">
    <div style="background-image: url('SubBackground.png')">
          <div class="text-white text-center rgba-stylish-strong py-5 px-4">
              <div class="py-5">

                  <!--Content-->
                      <h6 class="orange-text font-bold"><i class="fa fa-camera-retro"></i> Wecome To</h6>
                      <h1 class="card-title pt-3 mb-5 font-bold">Shuffler</h1>
                     
                      <p><a class="btn-danger btn-lg"href="/login"role="button">Login</a> <a class="btn-success btn-lg" href="/register" role="button">Register</a></p>
                  <!--Content-->
                  
                   <p> please login or register to use the service</p>
                  
              </div>
          </div>
      </div>
  </div>
</div>
<!--Jumbotron-->


@endsection
