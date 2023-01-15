@extends ('layouts.headerfooter')

@section('nuorodos')
    <li>
        <a href="{{ route('logout') }}" class="nav-link mr-5 pr-5"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Atsijungti</a>
    </li>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endsection
        
@section('turinys')
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif
    <div class="row">
        <div class="col-12 col-lg-4 container-fluid">
            @yield('roles')
        </div>
        <div class="col-12 col-lg-4 container-fluid">
            @yield('meniu')
        </div>
        <div class="col-12 col-lg-4 container-fluid">
            @yield('projektai')
        </div>
    </div>
@endsection
