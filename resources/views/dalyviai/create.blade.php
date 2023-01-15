@extends('layouts.forma')

@section('title')
    Dalyvio kūrimas
@endsection

@section('forma')
<div class="row container-fluid">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Dalyvio kūrimas</h5>
            </div>
            @if(session('status'))
                <div class="alert alert-success mb-1 mt-1">{{ session('status') }}</div>
            @endif
            <div class="card-body">
                <form action="{{ route('projektas.dalyviai.store', $projektas) }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Dalyvio pavadinimas:</strong>
                                <input type="text" name="pavadinimas" class="form-control mx-auto w-75" maxlength="50" 
                                       placeholder="Dalyvio pavadinimas" value="{{ old('pavadinimas') }}">
                                @error('pavadinimas')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="roles"><strong>Pasirinkite rolę:</strong></label>
                                <select name="role">
                                    @foreach ($roles as $role)
                                        @if($role->id == old('roles_id'))
                                            <option value="{{ $role->id}}" selected>{{ $role->pavadinimas }}</option>
                                        @else
                                            <option value="{{ $role->id }}">{{ $role->pavadinimas }}</option>
                                        @endif 
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="darbuotojas"><strong>Pasirinkite darbuotoją*:</strong></label>
                                <select name="darbuotojas">
                                    <option selected></option>
                                    @foreach ($darbuotojai as $darbuotojas)
                                        @if($darbuotojas->id == old('darbuotojai'))
                                            <option value="{{ $darbuotojas->id}}" selected>{{ $darbuotojas->vardas." ".$darbuotojas->pavarde }}</option>
                                        @else
                                            <option value="{{ $darbuotojas->id}}">{{ $darbuotojas->vardas." ".$darbuotojas->pavarde }}</option>
                                        @endif
                                    @endforeach
                                </select>   
                                @error('darbuotojas')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                                @error('jauPridetas')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-2"><i>*Palikti tuščią, jeigu kuriamas išorinis dalyvis.</i></div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Dalyvavimo pradžios data:</strong>
                                <input type="date" name="pradzia" class="form-control mx-auto w-75"
                                       value="{{ old('pradzia') }}" min="{{ $pradzia }}" max="{{ $pabaiga }}" required>
                                @error('pradzia')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <strong>Dalyvavimo pabaigos data:</strong>
                                <input type="date" name="pabaiga" class="form-control mx-auto w-75"
                                       value="{{ old('pabaiga') }}" min="{{ $pradzia }}" max="{{ $pabaiga }}" required>
                                @error('pabaiga')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>     
                        <div class="col-12">
                            <div class="col-12 mb-2">
                                <button type="submit" class="btn btn-primary w-100">Patvirtinti</button>
                            </div>
                            <div class="col-12">
                                <a class="btn btn-secondary w-100" href="{{ route('projektas.dalyviai.index', $projektas) }}">Grįžti</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection