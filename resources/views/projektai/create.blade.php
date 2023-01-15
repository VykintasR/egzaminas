@extends('layouts.forma')

@section('title')
    Naujo projekto kūrimas
@endsection

@section('forma')
<div class="row container-fluid">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Naujo projekto kūrimas</h5>
            </div>
            @if(session('status'))
            <div class="alert alert-success mb-1 mt-1"> {{ session('status') }}</div>
            @endif
            <div class="card-body">
                <form action="{{ route('projektai.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Projekto pavadinimas:</strong>
                                <input type="text" name="pavadinimas" class="form-control mx-auto w-75" maxlength="150" 
                                    placeholder="Projekto pavadinimas" value="{{ old('pavadinimas') }}">
                                @error('pavadinimas')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Projekto aprašymas:</strong>
                                <textarea name="aprasymas" style="height: 150px" class="form-control mx-auto w-75" 
                                        placeholder="tekstas iki 3000 simbolių" maxlength="3000">{{ old('aprasymas') }}</textarea>
                                @error('aprasymas')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Planuojama pradžios data:</strong>
                                <input type="date" name="pradzia" class="form-control mx-auto w-75"
                                    value="{{ old('pradzia') }}">
                                @error('pradzia')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Planuojama pabaigos data:</strong>
                                <input type="date" name="pabaiga" class="form-control mx-auto w-75"
                                    value="{{ old('pabaiga') }}">
                                @error('pabaiga')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Projekto biudžetas (€):</strong>
                                <input type="number" name="biudzetas" class="form-control mx-auto w-75"
                                    min="0" step=".01" value="{{ old('biudzetas') }}">
                                @error('biudzetas')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="col-12 mb-2">
                                <button type="submit" class="btn btn-primary w-100">Patvirtinti</button>
                            </div>
                            <div class="col-12">
                                <a class="btn btn-secondary w-100" href="{{ route('projektai.index') }}">Grįžti</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection