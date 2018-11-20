<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="{{ __('Toggle navigation') }}"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse"
            id="navbarSupportedContent"
        >    <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

                <li class="dropdown">
                    <a
                        class="nav-link dropdown-toggle"
                        data-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        Browse
                    </a>
                    
                    <ul class="dropdown-menu">
                        <li>
                            <a class="nav-link" href="/threads">
                                All Threads
                            </a>
                        </li>

                        @if (auth()->check())
                        
                            <li>
                                <a class="nav-link"
                                 href="/threads?by={{ auth()->user()->name }}"
                                >
                                    My Threads
                                </a>
                            </li>
                            
                        @endif

                        <li>
                            <a class="nav-link" href="/threads?popular=1">
                                Popular All Time
                            </a>
                        </li>

                        <li>
                            <a class="nav-link" href="/threads?unanswered=1">
                                Unanswered Threads
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/threads/create">
                        New Threads
                    </a>
                </li>
                
                <li class="dropdown">
                    <a
                        class="nav-link dropdown-toggle"
                        data-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        Channels
                    </a>
                    <ul class="dropdown-menu">
                        @foreach ($channels as $channel)
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="/threads/{{ $channel->slug }}"
                                >
                                    {{ $channel->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
                    
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            {{ __('Login') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">
                                {{ __('Register') }}
                            </a>
                        @endif
                    </li>
                @else

                    <user-notifications></user-notifications>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown"
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            v-pre
                        >
                            {{ Auth::user()->name }} 
                            <span class="caret"></span>
                        </a>

                        

                        <ul class="dropdown-menu dropdown-menu-right"
                            aria-labelledby="navbarDropdown"
                        >
                            <li class="nav-item">
                                <a class="nav-link"
                                 href="{{ route('logout') }}"
                                 onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();"
                                >
                                    {{ __('Logout') }}
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link"
                                 href="{{ route('profile', Auth::user()) }} "
                                >
                                    My Profile
                                </a>
                            </li>

                            <form id="logout-form"
                             action="{{ route('logout') }}"
                             method="POST"
                             style="display: none;"
                            >
                                @csrf
                            </form>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>