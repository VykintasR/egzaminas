@extends('layouts.valdymas')

@section('title')
    Projekto veiklos
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
        <h3 class="text-center mt-2 mb-3">Veiksmai</h3>
        <a class="btn btn-secondary w-100 mb-2" href="{{ route('projektai.show', $projektas) }}">Grįžti</a>
        @can('isAdmin')
        <a class="btn btn-success w-100" href="{{ route('projektas.veiklos.create', $projektas) }}">Nauja veikla</a>
        @endcan

    </div>
    <div class="col-2"></div>
</div>
@endsection

@section('pagrindinis')
<div class="row">
    <div class="col-12 margin-tb text-center">
        <h2>Projekto</h2>
        <h4>{{ $projektas->pavadinimas }}</h4>
        @can('isAdmin')
            <h3>veiklos</h3>
        @endcan
        @cannot('isAdmin')
            <h3>priskirtos veiklos</h3>
        @endcan
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @error('neraTeisiu')
        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
    @enderror
    </div>
</div>
<div class="row">
@foreach ($veiklos as $veikla) 
    <div class="col-sm-12 col-md-8 col-lg-6 col-xl-4 mb-3">
        <div class="row">
            <div class="col-11 m-1">
                <div class="row">
                    <div class="col-12 border border-dark">
                        <div class="row border border-dark">
                            <div class="col-12 border-top border-dark">
                                <div class="row">
                                    <div class="col-12 text-center border-bottom border-dark">
                                        <h3>{{ $veikla->pavadinimas }}</h3>
                                    </div>
                                    @if($veikla->aprasymas != null)
                                    <div class="col-12 overflow-auto w-100" style="height:150px"><strong>Aprašymas:</strong> {{ $veikla->aprasymas}} </div>
                                    @endif
                                    <div class="col-12"><strong>Biudžetas:</strong>{{ $veikla->biudzetas }} €</div>
                                    <div class="col-12"><strong>Prioritetas:</strong> {{ $veikla->prioritetas }} </div>
                                </div>    
                            </div>
                            <div class="col-12 border-top border-dark">
                                <div class="row">
                                    <div class="col-6"><small><strong>Projekto pradžia:</strong></small></div>
                                    <div class="col-6"><small><strong>Projekto pabaiga:</strong></small></div>
                                    <div class="col-6">
                                        <small>
                                            {{ $projektas->reali_pradzios_data != null ? $projektas->reali_pradzios_data : $projektas->planuojama_pradzios_data }}
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <small>
                                            {{ $projektas->reali_pabaigos_data != null ? $projektas->reali_pabaigos_data : $projektas->planuojama_pabaigos_data }}
                                        </small>
                                    </div>
                                    <div class="col-6"><small><strong>Veiklos pradžia:</strong></small></div>
                                    <div class="col-6"><small><strong>Veiklos pabaiga:</strong></small></div>
                                    <div class="col-6"><small>{{ $veikla->planuojama_pradzios_data }}</small></div>
                                    <div class="col-6"><small>{{ $veikla->planuojama_pabaigos_data }}</small></div>
                                </div>
                            </div>
                            <div class="col-12 border-top border-dark">
                                <strong>Numatyta veiklos trukmė:</strong> {{ $veikla->trukme }} 
                            </div>
                            <div class="col-12 border-top border-dark">
                                <form class="row p-3" method="Post" action="{{ route('projektas.veiklos.destroy', ['projektas' => $projektas, 'veikla' => $veikla]) }}">
                                @csrf
                                @method('DELETE')
                                    <a class="col-12 btn btn-info w-100 mb-2" href="{{ route('projektas.veiklos.show',['projektas' => $projektas, 'veikla' => $veikla]) }}">Plačiau</a>
                                    @can('isAdmin')
                                    <a class="col-12 btn btn-primary mb-2" href="{{ route('projektas.veiklos.edit', ['projektas' => $projektas, 'veikla' => $veikla]) }}">Redaguoti</a>
                                    <button type="submit" class="col-12 btn btn-danger">Pašalinti</button>
                                    @endcan
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1">
            </div> 
        </div>
    </div>
@endforeach
</div>
{!! $veiklos->links() !!}
@endsection