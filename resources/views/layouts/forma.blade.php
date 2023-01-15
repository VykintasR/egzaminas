@extends('layouts.headerfooter')

@section('turinys')
<div class="row text-center container-fluid">
    <div class="col-sm-12 col-md-2 col-lg-3 col-xl-4"></div>
    <div class="col-sm-12 col-md-8 col-lg-6 col-xl-4">
        @yield('forma')  
    </div>
    <div class="col-sm-12 col-md-2 col-lg-3 col-xl-4"></div>
</div>
@endsection
