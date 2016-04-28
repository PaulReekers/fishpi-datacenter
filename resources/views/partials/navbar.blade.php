<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding -->
            <a class="navbar-brand" href="{{ url('/') }}">
                FishPi Datacenter
            </a>
        </div>

        <!-- Navbar Right -->
        <div class="collapse navbar-collapse" id="app-navbar-collapse">

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li class="{{ set_active('/')}}"><a href="{{ url('/') }}">Home</a></li>
                <li class="{{ set_active('how-to')}}"><a href="{{ url('/how-to') }}">How to</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        @if (Auth::guest())
                            Member
                        @else
                            {{ Auth::user()->name }}
                        @endif
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li class="{{ set_active('login')}}"><a href="{{ url('/login') }}">Login</a></li>
                            <li class="{{ set_active('register')}}"><a href="{{ url('/register') }}">Register</a></li>
                        @else
                            <li class="{{ set_active('admin')}}"><a href="{{ url('/admin') }}"><i class="fa fa-btn fa-user"></i>Admin</a></li>
                            <li class="{{ set_active('logout')}}"><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
