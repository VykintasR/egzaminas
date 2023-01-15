@extends('layouts.valdymas')

@section('title')
    Projekto dalyviai
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
        <a class="btn btn-secondary w-100 mb-2" href="{{ route('projektai.show', $projektas->id) }}">Grįžti</a>  
        @can('isAdmin')
            <a class="btn btn-success w-100" href="{{ route('projektas.dalyviai.create', $projektas) }}">Naujas dalyvis</a>
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
        <h3>Dalyviai</h3>
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
@foreach ($dalyviai as $dalyvis) 
    <div class="col-sm-12 col-md-8 col-lg-6 col-xl-4 mb-3">
        <div class="row">
            <div class="col-11 m-1">
                <div class="row">
                    <div class="col-12 border border-dark">
                        <div class="row border border-dark">
                            <div class="col-12 text-center border border-dark">
                                <h3>{{ $dalyvis->pavadinimas }}</h3>
                            </div>
                            <div class="col-12 border-top border-dark">
                                <div class="row">
                                    <div class="col-12"><strong>Rolė:</strong> {{ $dalyvis->role }}</div>
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
                                    <div class="col-6"><small><strong>Dalyvavimo pradžia:</strong></small></div>
                                    <div class="col-6"><small><strong>Dalyvavimo pabaiga:</strong></small></div>
                                    <div class="col-6"><small>{{ $dalyvis->dalyvavimo_pradzios_data }}</small></div>
                                    <div class="col-6"><small>{{ $dalyvis->dalyvavimo_pabaigos_data }}</small></div>
                                </div>
                            </div>
                            <div class="col-12 border-top border-dark">
                                <strong>Dalyvavimo trukmė:</strong> {{ $dalyvis->dalyvavimo_trukme }}
                            </div>
                            @can('isAdmin')
                            <div class="col-12 border-top border-dark">
                                <form class="row p-3" action="{{ route('projektas.dalyviai.destroy', ['projektas' => $projektas, 'dalyvis' => $dalyvis]) }}" method="Post">
                                @csrf
                                @method('DELETE')    
                                    <a class="col-12 btn btn-primary mb-2" href="{{ route('projektas.dalyviai.edit', ['projektas' => $projektas, 'dalyvis' => $dalyvis]) }}">Redaguoti</a>
                                    <button type="submit" class="col-12 btn btn-danger">Pašalinti</button>
                                </form>        
                            </div>
                            @endcan
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
{!! $dalyviai->links() !!}
@endsection