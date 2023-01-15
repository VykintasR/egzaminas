@extends('layouts.headerfooter')

@section('title')
Pradžia
@endsection

@section('nuorodos')
@if (Route::has('login'))
    @auth
    <li class="nav-item">
        <a href="{{ route('pagrindinis')}}" class="nav-link mr-5 pr-5">Pradžia</a>
    </li>
    <li>
        <a href="{{ route('logout') }}" class="nav-link mr-5 pr-5"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Atsijungti</a>
    </li>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @else
    <li>
        <a href="{{ route('login') }}" class="nav-link mr-5 pr-5">Prisijungti</a>
    </li>
    @if (Route::has('register'))
    <li>
        <a href="{{ route('register') }}" class="nav-link mr-5 pr-5">Registruotis</a>
    </li>
    @endif
    @endauth
@endif
@endsection

@section('turinys')
<div class="row mt-5">
    <div class="col-12 col-lg-4 container-fluid"></div>
    <div class="col-12 col-lg-4 container-fluid mt-5">
        <h5>Project Assistant - tai projektų planavimo programinė įranga, leidžianti:</h5>
        <div class="text-justify">
            <ul>
                <li>Priskirti projektams išorinius dalyvius ir darbuotojus</li>
                <li>Kurti veiklas ir skirstyti jas darbuotojams</li>
                <li>Pateikti darbuotojams tik jiems aktualius projektus ir veiklas</li>
                <li>Fiksuoti projekto ar jo veiklų vykdymo pradžios ir pabaigos įvykius, biudžeto pokytį, išsaugant planuotus duomenis</li>
                <li>Priskirti veikloms prioritetą šimtabalėje sistemoje</li>
                <li>Koreguoti informaciją betkuriuo momentu ir visiems iškart matyti pakeitimus</li>
            </ul>
        </div>
        <p class="text-justify">
            Programinė įranga skirta projektų ir jų veiklų planavimo informacijos talpinimui. Atliekant redagavimą ir vykdomas griežtas
            įvestų duomenų patikrinimas, kad apsaugoti nuo atsitiktinių klaidų ir nepažeisti duomenų vientisumo.
            Įrankio sąsaja orientuota į paprastumą ir pastovumą, meniu veiksmams naudojant vienodą išdėstymo strategiją ir spalvų sistemą.
        </p>
    </div>
    <div class="col-12 col-lg-4 container-fluid"></div>
</div>
@endsection
