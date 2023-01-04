{{-- Navigation --}}
<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm mt-auto py-2">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            Skilled Trades
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- Right Side Of Navbar --}}
            <ul class="navbar-nav ms-auto ml-auto">
                {{-- Guest User Links --}}
                @guest
                    {{-- Home Link --}}
                    @if (Route::has('home'))
                        <li class="{{ (request()->is('home') || (request()->is('/')) ) ? 'nav-item active' : 'nav-item' }}">
                            <a class="nav-link" href="{{ route('home') }}"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                        </li>
                    @endif
                    {{-- Trade Place Link --}}
                    @if (Route::has('trade'))
                        <li class="{{ (request()->is('trade')) ? 'nav-item active' : 'nav-item' }}">
                            <a class="nav-link" href="{{ route('trade') }}"><i class="fa fa-bars"></i> {{ __('Trade Place') }}</a>
                        </li>
                    @endif
                    {{-- About Link --}}
                    @if (Route::has('about'))
                        <li class="{{ (request()->is('about')) ? 'nav-item active' : 'nav-item' }}">
                            <a class="nav-link" href="{{ route('about') }}"><i class="fa fa-info-circle"></i> {{ __('About') }}</a>
                        </li>
                    @endif
                    {{-- Login Link --}}
                    @if (Route::has('login'))
                        <li class="{{ (request()->is('login')) ? 'nav-item active' : 'nav-item' }}">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fa fa-sign-in"></i> {{ __('Login') }}</a>
                        </li>
                    @endif
                    {{-- Register Link --}}
                    @if (Route::has('register'))
                        <li class="{{ (request()->is('register')) ? 'nav-item active' : 'nav-item' }}">
                            <a class="nav-link btn btn-info btn-sm text-white rounded" href="{{ route('register') }}"><i class="fa fa-user-plus"></i> {{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    {{-- Authentication Links --}}
                    @auth
                        @if (Route::has('login'))
                            @auth
                                {{-- Home Link --}}
                                @if (Route::has('home'))
                                    <li class="{{ (request()->is('home') || (request()->is('/')) ) ? 'nav-item active' : 'nav-item' }}">
                                        <a class="nav-link" href="{{ route('home') }}"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                                    </li>
                                @endif
                                {{-- Trade Place Link --}}
                                @if (Route::has('trade'))
                                    <li class="{{ (request()->is('trade')) ? 'nav-item active' : 'nav-item' }}">
                                        <a class="nav-link" href="{{ route('trade') }}"><i class="fa fa-bars"></i> {{ __('Trade Place') }}</a>
                                    </li>
                                @endif
                                {{-- About Link --}}
                                @if (Route::has('about'))
                                    <li class="{{ (request()->is('about')) ? 'nav-item active' : 'nav-item' }}">
                                        <a class="nav-link" href="{{ route('about') }}"><i class="fa fa-info-circle"></i> {{ __('About') }}</a>
                                    </li>
                                @endif

                                {{-- Admin menu --}}
                                @if(Auth::user()->is_admin) 
                                    <!-- Users Link -->
                                    @if (Route::has('users'))
                                        <li class="{{ (request()->is('users')) ? 'nav-item active' : 'nav-item' }}">
                                            <a class="nav-link" href="{{ route('users') }}"><i class="fa fa-user"></i> {{ __('Users') }}</a>
                                        </li>
                                    @endif
                                @endif

                                {{-- Admin menu --}}
                                @if(Auth::user()->is_admin) 
                                    {{-- Settings Link --}}
                                    @if (Route::has('settings'))
                                        <li class="{{ (request()->is('settings')) ? 'nav-item active' : 'nav-item' }}">
                                            <a class="nav-link" href="{{ route('settings') }}"><i class="fa fa-gear"></i> {{ __('Settings') }}</a>
                                        </li>
                                    @endif
                                @endif
                            @endauth     
                        @endif
                    @endauth

                    {{-- Dropdown links menu --}}
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fa fa-user-circle"></i> {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            {{-- My Ads Link --}}
                            <a class="dropdown-item" href="{{ route('my-ads') }}">
                                {{ __('My Ads') }}
                            </a>
                            {{-- My Favourites Link --}}
                            <a class="dropdown-item" href="{{ route('my-favourites') }}">
                                {{ __('My Favourites') }}
                            </a>
                            {{-- My Bookings Link --}}
                            <a class="dropdown-item" href="{{ route('my-bookings') }}">
                                {{ __('My Bookings') }}
                            </a>
                            {{-- My Profile Link --}}
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                {{ __('My Profile') }}
                            </a>
                            {{-- My Reports Link --}}
                            <a class="dropdown-item" href="{{ route('reports') }}">
                                {{ __('My Reports') }}
                            </a>
                            
                            <div class="dropdown-divider"></div>

                            {{-- Logout Link --}}
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }} <i class="fa fa-sign-out"></i> 
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>