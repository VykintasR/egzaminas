@extends('layouts.valdymas')

@section('title')
    Projektų sąrašas
@endsection

@section('meniu')
<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <div class="row">
            <h3 class="col-12 text-center mt-2 mb-2">Veiksmai</h3>
            <a class="btn btn-secondary w-100 mb-2" href="{{ route('pagrindinis') }}">Grįžti</a>
            @can('isAdmin')
                <a class="col-12 btn btn-success w-100 mb-2" href="{{ route('projektai.create') }}"> Naujas projektas</a>
            @endcan
            <a class="btn btn-info w-100" href="{{ URL::to('/projektai/pdf') }}">Eksportuoti į PDF</a>
            <h3 class="col-12 text-center mt-2">Paieška</h3>
            <form class="col-12" action="{{ route('projektai.paieska') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="form-group">
                    <p>Projekto <b><i>planuojama</i></b> pradžios data nuo:</p>
                    <input type="date" name="data" class="form-control">
                    @error('data')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group"> 
                    <button class="w-100 btn btn-primary" type="submit">Ieškoti</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-2"></div>
</div>
@endsection

@section('pagrindinis')
<div class="row">
    <div class="col-lg-12 text-center margin-tb">
        <h2>Projektų sąrašas</h2>
    </div>
</div>
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif
@if(!$projektai->isEmpty())
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Projekto pavadinimas</th>
            <th>Projekto aprašymas</th>
            <th>Planuojama pradžios data</th>
            <th width="400px">Veiksmas</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($projektai as $projektas)
        <tr>
            <td>{{ $projektas->pavadinimas }}</td>
            <td class="col-12 overflow-auto w-100" style="height:200px">
                <div class="overflow-auto w-100 h-100"> {{ $projektas->aprasymas }}</div>
            </td>
            <td>{{ $projektas->planuojama_pradzios_data }}</td>
            <td>
                <form class="row" action="{{ route('projektai.destroy',$projektas->id) }}" method="Post">
                    @csrf
                    @method('DELETE')
                        <a class="col-12 btn btn-info mb-2" href="{{ route('projektai.show', $projektas->id) }}">Plačiau</a>
                    @can('isAdmin')
                        <a class="col-12 btn btn-warning mb-2" href="{{ route('projektai.edit', $projektas->id) }}">Koreguoti</a>
                        <button type="submit" class="col-12 btn btn-danger">Atšaukti</button>
                    @endcan
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! $projektai->links() !!}
@else
    <p class="text-center">Nėra sukurtų arba priskirtų projektų.</p>
@endif
@endsection