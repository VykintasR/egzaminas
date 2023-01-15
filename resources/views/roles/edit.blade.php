@extends('layouts.forma')

@section('title')
Rolės Koregavimas
@endsection


@section('forma')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Rolės koregavimas</h5>
        </div>
        @if(session('status'))
        <div class="alert alert-success mb-1 mt-1">
            {{ session('status') }}
        </div>
        @endif
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <strong>Rolės pavadinimas:</strong>
                            <input type="text" name="pavadinimas"class="form-control mx-auto w-75"  maxlength="50" placeholder="Rolės pavadinimas">
                            @error('pavadinimas')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="col-12 mb-2">
                            <button type="submit" class="btn btn-primary w-100">Patvirtinti</button>
                        </div>
                        <div class="col-12">
                            <a class="btn btn-secondary w-100" href="{{ route('pagrindinis') }}">Grįžti</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection