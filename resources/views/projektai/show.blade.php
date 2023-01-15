@extends('layouts.valdymas')

@section('title')
    Projekto informacija 
@endsection

@section('meniu')
<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <div class="row">
            <h3 class="col-12 text-center mt-2">Veiksmai</h3>
            <a class="col-12 border btn btn-secondary w-100 mb-2"  href="{{ url('/projektai') }}">Projektų sąrašas</a>
            <a class="col-12 btn btn-info w-100 mb-2" href="{{ route('projektas.dalyviai.index', $projektas) }}">Dalyviai</a>
            <a class="col-12 btn btn-info w-100 mb-2" href="{{ route('projektas.veiklos.index', $projektas) }}">Veiklos</a>
            @can('isAdmin')
                <a class="col-12 border btn btn-warning w-100 mb-2" href="{{ route('projektai.edit', $projektas) }}">Koreguoti informaciją</a>
                
            @if (empty($projektas->reali_pradzios_data))
                @if(empty($projektas->reali_pabaigos_data))
                    <form class="col-12 mb-3" action="{{ route('projektas.fiksuotiPradzia', $projektas) }}" method="Post">
                    @csrf
                    @method('POST') 
                        <input type="date" name="pradzios_data" class="form-control" value="{{ date("Y-m-d") }}" min="{{ $datosRiba}}">
                        @error('pradzios_data')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="w-100 btn btn-primary">Fiksuoti <i><b>realią</b></i> pradžios datą</button>
                    </form>
                @endif
            @else
                @if(empty($projektas->reali_pabaigos_data))
                    <form class="col-12 mb-3" action="{{ route('projektas.anuliuotiPradzia', $projektas) }}" method="Post">
                    @csrf
                        <button type="submit" class="w-100 btn btn-secondary w-100">Anuliuoti <i><b>realią</b></i> pradžios datą</button>
                    </form>
                @endif
            @endif

            @if (empty($projektas->reali_pabaigos_data))
                @if (!empty($projektas->reali_pradzios_data))
                    <form class="col-12 mb-3" action="{{ route('projektas.fiksuotiPabaiga', $projektas) }}" method="Post">
                    @csrf
                        <input type="date" name="pabaigos_data" class="form-control" value="{{ date("Y-m-d") }}" min="{{ $datosRiba }}">
                        @error('pabaigos_data')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                            <button type="submit" class="w-100 btn btn-primary">Fiksuoti <i><b>realią</b></i> pabaigos datą</button>
                    </form>
                @endif
            @else
                @if (!empty($projektas->reali_pradzios_data))
                    <form class="col-12 mb-3" action="{{ route('projektas.anuliuotiPabaiga', $projektas) }}" method="Post">
                    @csrf
                        <button type="submit" class="btn btn-secondary w-100">Anuliuoti <i><b>realią</b></i> pabaigos datą</button>
                    </form>
                @endif
            @endif

            <form class="col-12" action="{{ route('projektai.destroy', $projektas) }}" method="Post">
            @csrf
            @method('DELETE')     
                <button type="submit" class="w-100 btn btn-danger">Atšaukti projektą</button>
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
        <h3>Projekto informacija</h3>
    </div>
    @if ($message = Session::get('success'))
        <div class="col-12 alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="col-12 text-center">
        <h3 class="text-center">{{ $projektas->pavadinimas }}</h3>
        <h5 class="text-center">Aprašymas</h5>
        <div class="overflow-auto w-100 h-50"> {{ $projektas->aprasymas }}</div>
        <div class="col-12">
            <p class=""><strong>Biudžetas: </strong><i> {{ $projektas->projekto_biudzetas }} €</i></p>

            @if (!empty($projektas->reali_pabaigos_data))
                <p><strong>Būsena:</strong><i> baigtas</i></p>
            @elseif (!empty($projektas->reali_pradzios_data))
                <p><strong>Būsena:</strong><i> pradėtas</i></p>
            @else
                <p><strong>Būsena:</strong><i> nepradėtas</i></p>
            @endif
        </div>
    </div>

    @if(empty($projektas->reali_pradzios_data))
        <div class="col-12 text-center">
            <p><strong>Pradžios data:</strong><i> {{ $projektas->planuojama_pradzios_data }}</i></p>
            <p><strong>Pabaigos data:</strong><i> {{ $projektas->planuojama_pabaigos_data }}</i></p>
            <p><strong>Trukmė: </strong><i> {{ $trukme }} </i></p>  
        </div>
    @else
    <div class="col-sm-12 col-lg-2"></div>
        <div class="col-sm-12 col-lg-4">
            <p><strong>Planuota pradžios data:</strong><i> {{ $projektas->planuojama_pradzios_data }}</i></p>
            <p><strong>Planuota pabaigos data:</strong><i> {{ $projektas->planuojama_pabaigos_data }}</i></p>
            <p><strong>Planuojama trukmė: </strong><i> {{ $trukme }} </i></p>  
        </div>
    <div class="col-sm-12 col-lg-4">
        @if(!empty($projektas->reali_pabaigos_data))
            <p><strong>Pradžios data: </strong><i> {{ $projektas->reali_pradzios_data}} </i></p>
            <p><strong>Pabaigos data: </strong><i> {{ $projektas->reali_pabaigos_data}} </i></p>
            <p><strong>Reali trukmė: </strong> <i> {{ $realiTrukme }} </i></p>
        @else
            <p><strong>Pradžios data: </strong><i> {{ $projektas->reali_pradzios_data}} </i></p>
            <p><strong>Pabaigos data: </strong><i> nenumatyta </i></p>
        @endif
    </div>
    <div class="col-sm-12 col-lg-2"></div>
    @endif

    <div class="col-12 text-center">
        @if($veikluSkaicius != 0)
        <p>
            <i><strong>{{ $veikluSkaicius }}</strong></i> veiklų -
            <i><strong>{{ $baigtuVeikluSkaicius }}</strong></i> užbaigtų ir
            <i><strong>{{ $nebaigtuVeikluSkaicius }}</strong></i> neužbaigtų.
        </p>
        @else
            <h5 class="mt-2 text-danger">Projektas neturi veiklų.</h5>
        @endif
        @if($dalyviuSkaicius+$darbuotojuSkaicius != 0)
        <p>
            <i><strong>{{ $dalyviuSkaicius+$darbuotojuSkaicius }}</strong></i> dalyviai -
            <i><strong>{{ $dalyviuSkaicius }}</strong></i> išoriniai ir
            <i><strong>{{ $darbuotojuSkaicius }}</strong></i> darbuotojai.
        </p>
        @else
            <h5 class="mt-2 text-danger">Projektas neturi dalyvių.</h5>
        @endif
        @if($darbuotojuSkaicius != 0 && !$projektoDarbuotojai->isEmpty())
            <h5 class="mt-2">Darbuotojai:</h5>
                @foreach ($projektoDarbuotojai as $darbuotojas)
                    <p>{{ $darbuotojas->vardas." ".$darbuotojas->pavarde }}</p>
                @endforeach
        @else
            <h5 class="mt-2 text-danger">Nėra įtrauktas nei vienas darbuotojas.</h5>
        @endif
    </div>
    <div class="col-sm-12 col-lg-3"></div>
</div>
@endsection