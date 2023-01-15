@extends('layouts.forma')

@section('title')
    Veiklos kūrimas
@endsection

@section('forma')
<div class="row container-fluid">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Veiklos kūrimas</h5>
            </div>
            @if(session('status'))
                <div class="alert alert-success mb-1 mt-1">{{ session('status') }}</div>
            @endif
            <div class="card-body">
                <form action="{{ route('projektas.veiklos.store', $projektas) }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Veiklos pavadinimas:</strong>
                                <input type="text" name="pavadinimas" class="form-control mx-auto w-75" maxlength="50"
                                       placeholder="Veiklos pavadinimas" value="{{ old('pavadinimas') }}" required>
                                @error('pavadinimas')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Veiklos aprašymas(nebūtinas):</strong>
                                <textarea name="aprasymas" style="height: 150px" class="form-control mx-auto w-75" maxlength="1000"  
                                        placeholder="tekstas iki 1000 simbolių.">{{ old('aprasymas') }}</textarea>
                                @error('aprasymas')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Prioritetas:</strong>
                                <input type="number" name="prioritetas" class="form-control mx-auto w-75" placeholder="0-100"
                                    min="0" max="100" value="{{ old('prioritetas') }}" required>
                                @error('prioritetas')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="darbuotojai">
                                    <strong>Pasirinkite darbuotoją:</strong>
                                </label>
                                <select name="darbuotojas">
                                    @foreach ($darbuotojai as $darbuotojas)
                                        @if($darbuotojas->id == old('darbuotojas'))
                                            <option value="{{ $darbuotojas->id}}" selected>{{ $darbuotojas->vardas." ".$darbuotojas->pavarde }}</option>
                                        @else
                                            <option value="{{ $darbuotojas->id }}">{{ $darbuotojas->vardas." ".$darbuotojas->pavarde }}</option>
                                        @endif 
                                    @endforeach
                                </select>
                                @error('darbuotojas')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Planuojama pradžios data:</strong>
                                <input type="date" name="pradzia" class="form-control mx-auto w-75"
                                       value="{{ old('pradzia') }}" min="{{ $pradzia }}" max="{{ $pabaiga }}" required>
                                @error('pradzia')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Planuojama pabaigos data:</strong>
                                <input type="date" name="pabaiga" class="form-control mx-auto w-75"
                                       value="{{ old('pabaiga') }}" min="{{ $pradzia }}" max="{{ $pabaiga }}" required>
                                @error('pabaiga')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Planuojamas veiklos biudžetas (€):</strong>
                                <input type="number" name="biudzetas" class="form-control mx-auto w-75"
                                    min="0" step=".01" max="{{ $laisvasBiudzetas }}" value="{{ old('biudzetas') }}" required>
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
                                <a class="btn btn-secondary w-100" href="{{ route('projektas.veiklos.index', $projektas) }}">Grįžti</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection