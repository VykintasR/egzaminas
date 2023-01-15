@extends('layouts.headerfooter')

@section('nuorodos')
    @yield('papildomosnuorodos')
<li class="nav-item">
    <a class="nav-link mr-5 pr-5" href="{{ url('/pradzia') }}">Pradžia</a>
</li>
<li>
    <a href="{{ route('logout') }}" class="nav-link mr-5 pr-5"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Atsijungti</a>
</li>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection
        
@section('turinys')
<div class="row">
    <div class="col-sm-12 col-lg-3 container-fluid">
        @yield('meniu')
    </div>
    <div class="col-sm-12 col-lg-9 container-fluid">
        @yield('pagrindinis')
    </div>
</div>
@endsection
