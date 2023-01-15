@extends('layouts.valdymas')

@section('title')
    Veiklos informacija
@endsection

@section('papildomosnuorodos')
<li class="nav-item">
    <a class="nav-link mr-5 pr-5" href="{{ route('projektai.index') }}">Projektų sąrašas</a>
</li>
@endsection

@section('meniu')
<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <div class="row">
            <h3 class="col-12 text-center mt-2 mb-2">Veiksmai</h3>
            <a class="col-12 btn btn-secondary w-100 mb-2 " href="{{ route('projektas.veiklos.index', $projektas) }}">Grįžti į veiklų sąrašą</a>
            @can('isAdmin')
                <a class="col-12 btn btn-warning w-100 mb-2" href="{{ route('projektas.veiklos.edit', ['projektas' => $projektas, 'veikla' => $veikla]) }}">Redaguoti veiklos informaciją</a>
                @if (empty($veikla->reali_pradzios_data))
                    @if(empty($veikla->reali_pabaigos_data))
                        <form class="col-12 mb-3" method="Post" action="{{ route('veikla.fiksuotiPradzia', ['projektas' => $projektas, 'veikla' => $veikla]) }}">
                        @csrf
                            <input type="date" name="pradzios_data" class="form-control" value="{{ date("Y-m-d") }}" min="{{ $pradziosRiba}}" max="{{ $pabaigosRiba }}">
                            @error('pradzios_data')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-primary w-100">Fiksuoti pradžios datą</button>
                        </form>
                    @endif
                @else
                    @if(empty($veikla->reali_pabaigos_data))
                        <form class="col-12 mb-3" method="Post" action="{{ route('veikla.anuliuotiPradzia', ['projektas' => $projektas, 'veikla' => $veikla]) }}">
                        @csrf
                            <button type="submit" class="btn btn-secondary w-100">Anuliuoti pradžios datą</button>
                        </form>
                    @endif
                @endif

                @if (empty($veikla->reali_pabaigos_data))
                    @if (!empty($veikla->reali_pradzios_data))
                        <form class="col-12 mb-3" method="Post" action="{{ route('veikla.fiksuotiPabaiga', ['projektas' => $projektas, 'veikla' => $veikla]) }}">
                            @csrf
                                <input type="date" name="pabaigos_data" class="form-control" value="{{ date("Y-m-d") }}" min="{{ $pradziosRiba }}" max="{{ $pabaigosRiba }}">
                                @error('pabaigos_data')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                                <button type="submit" class="btn btn-primary w-100">Fiksuoti pabaigos datą</button>
                        </form>
                    @endif
                @else
                    @if (!empty($veikla->reali_pradzios_data))
                        <form class="col-12 mb-3" method="Post"
                            action="{{ route('veikla.anuliuotiPabaiga', ['projektas' => $projektas, 'veikla' => $veikla]) }}" >
                        @csrf
                            <button type="submit" class="col-12 btn btn-secondary w-100">Anuliuoti pabaigos datą</button>
                        </form>
                    @endif
                @endif
                
                @if (empty($veikla->realus_biudzetas))
                    <div class="col-12 mb-2">Laisvas biudžetas: {{ $nepriskirtasBiudzetas }} € </div>
                    <form class="col-12 mb-3" method="Post" action="{{ route('veikla.fiksuotiBiudzeta', ['projektas' => $projektas, 'veikla' => $veikla]) }}">
                    @csrf
                        <input type="number" name="biudzetas" class="form-control" step=".01"  value="{{ old('biudzetas') }}" min="0" max="{{  $nepriskirtasBiudzetas }}">
                        @error('biudzetas')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                        <button type="submit" class=" btn btn-primary w-100">Fiksuoti realų biudžetą</button>
                    </form>
                @else
                    <form class="col-12 mb-3" method="Post" action="{{ route('veikla.anuliuotiBiudzeta', ['projektas' => $projektas, 'veikla' => $veikla]) }}" >
                    @csrf
                        <button type="submit" class="col-12 btn btn-secondary w-100">Anuliuoti realų biudžetą</button>
                    </form>
                @endif

                <form class="col-12 mb-3" action="{{ route('projektas.veiklos.destroy', ['projektas' => $projektas, 'veikla' => $veikla]) }}" method="Post">
                @csrf
                @method('DELETE') 
                    <button type="submit" class="btn btn-danger w-100">Pašalinti veiklą</button>
                </form>

                <h3 class="col-12 text-center mt-2 mb-2">Darbuotojų valdymas</h3>
                <div class="col-12 mb-2">Nepriskirti darbuotojai:</div>
                <form class="col-12 mb-3" method="Post" action="{{ route('veikla.pridetiDarbuotoja', ['projektas' => $projektas, 'veikla' => $veikla]) }}">
                @csrf
                    <select class="w-100 mb-2" name="nepriskirtasDarbuotojas" >
                        @foreach ($nepriskirtiDarbuotojai as $darbuotojas)
                            @if($darbuotojas->id == old('nepriskirtasDarbuotojas'))
                                <option value="{{ $darbuotojas->id}}" selected>{{ $darbuotojas->vardas." ".$darbuotojas->pavarde }}</option>
                            @else
                                <option value="{{ $darbuotojas->id}}">{{ $darbuotojas->vardas." ".$darbuotojas->pavarde }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('pridedamasNeDalyvis')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                    @error('nepriskirtasDarbuotojas')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                    @error('jauPriskirtas')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success w-100">Įtraukti pasirinktą darbuotoją</button> 
                </form>
                <div class="col-12 mb-2">Veiklos darbuotojai:</div>
                <form class="col-12 mb-3" method="Post" action="{{ route('veikla.ismestiDarbuotoja', ['projektas' => $projektas, 'veikla' => $veikla]) }}">
                @csrf
                    <select class="w-100 mb-2" name="veiklosDarbuotojas">
                        @foreach ($veiklosDarbuotojai as $darbuotojas)
                            @if($darbuotojas->id == old('veiklosDarbuotojas'))
                                <option value="{{ $darbuotojas->id}}" selected>{{ $darbuotojas->vardas." ".$darbuotojas->pavarde }}</option>
                            @else
                                <option value="{{ $darbuotojas->id}}">{{ $darbuotojas->vardas." ".$darbuotojas->pavarde }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('ismetamasNeDalyvis')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                    @error('veiklosDarbuotojas')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                    @error('negalimasIsmetimas')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                    @error('paskutinisDarbuotojas')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                        <button type="submit" class="btn btn-danger w-100">Išbraukti pasirinktą darbuotoją</button>
                </form>
            @endcan
        </div>
    </div>
    <div class="col-2"></div>
</div>
@endsection

@section('pagrindinis')
<div class="row">
    <div class="col-12 text-center mb-4">
        <h3>veiklos informacija</h3>
    </div>
    @if ($message = Session::get('success'))
        <div class="col-12 alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @error('neraTeisiu')
        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
    @enderror

    <div class="col-12 text-center">
        <div>
        <h3 class="text-center">{{ $veikla->pavadinimas }}</h3>
        @if($veikla->aprasymas != null)
            <h5 class="text-center">Aprašymas</h5>
            <div class="overflow-auto w-100 h-50"> {{ $veikla->aprasymas }}</div>
        @endif
        </div>
        
        <p><strong>Biudžetas: </strong><i> {{ $veikla->realus_biudzetas != null ? $veikla->realus_biudzetas : $veikla->planuojamas_biudzetas }} €</i></p>

        @if(!empty($veikla->realus_biudzetas))
            <p><strong>Numatytas biudžetas: </strong><i>{{ $veikla->planuojamas_biudzetas }} €</i></p>
            <p><strong>Realus biudžetas: </strong><i> {{ $veikla->realus_biudzetas }} €</i></p>
        @endif
        @if (!empty($veikla->reali_pabaigos_data))
            <p><strong>Būsena:</strong><i> baigta</i></p>
        @elseif (!empty($veikla->reali_pradzios_data))
            <p><strong>Būsena:</strong><i> pradėta</i></p>
        @else
            <p><strong>Būsena:</strong><i> nepradėta</i></p>
        @endif
    </div>
        @if(empty($veikla->reali_pradzios_data))
            <div class="col-12 text-center mt-4">
                <p><strong>Pradžios data:</strong><i> {{ $veikla->planuojama_pradzios_data }}</i></p>
                <p><strong>Pabaigos data:</strong><i> {{ $veikla->planuojama_pabaigos_data }}</i></p>
                <p><strong>Trukmė: </strong><i> {{ $trukme }} </i></p>  
            </div>
        @else
            <div class="col-sm-12 col-lg-2"></div>
                <div class="col-sm-12 col-lg-4">
                    <p><strong>Planuota pradžios data:</strong><i> {{ $veikla->planuojama_pradzios_data }}</i></p>
                    <p><strong>Planuota pabaigos data:</strong><i> {{ $veikla->planuojama_pabaigos_data }}</i></p>
                    <p><strong>Planuota trukmė: </strong><i> {{ $trukme }} </i></p>  
                </div>
            <div class="col-sm-12 col-lg-4">

            @if (!empty($projektas->reali_pabaigos_data))
                <p><strong>Pradžios data: </strong><i> {{ $veikla->reali_pradzios_data}} </i></p>
                <p><strong>Pabaigos data: </strong><i> {{ $veikla->reali_pabaigos_data}} </i></p>
                <p><strong>Reali trukmė: </strong> <i> {{ $realiTrukme }} </i></p>
            @else
                <p><strong>Pradžios data: </strong><i> {{ $veikla->reali_pradzios_data}} </i></p>
                <p><strong>Pabaigos data: </strong><i> nenumatyta</i></p>
            @endif
            </div>
            <div class="col-sm-12 col-lg-2"></div>
        @endif
    </div>
</div>    
@endsection