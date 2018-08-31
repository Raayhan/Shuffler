<nav class="navbar navbar-expand-md navbar-dark bg-dark" 
style="position: fixed; margin: 0; padding: 0; 
left: 0vw; width: 100vw; z-index:1; 
display: flex; justify-content:center;">

    <div class="container">
        <input type="image" src="{{ asset('shuffle.PNG') }}" style= "max-width: 40px; max-height: 40px">
        @guest
            <a class="navbar-brand" style="" href="{{ url('/') }}">
                {{ config('app.name', 'Shuffler') }}
            </a>
        @else
            <a class="navbar-brand" style="" href="{{ url('/places') }}">
                {{ config('app.name', 'Shuffler') }}
            </a>
        @endguest
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <ul class="navbar-nav">
                {{-- @guest
                @else
                <li class="nav-item">
                    <a class="nav-link" href="/places">Places <span class="sr-only">(current)</span></a>
                </li>
                @endguest --}}
            </ul>

              <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                <li class="nav-item">
                    <a class="nav-link" href="/about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/services">Services</a>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>

        </div>
    </div>
    
</nav>
