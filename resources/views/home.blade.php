@extends('layouts.pradinis')

@section('roles')
    @can('isAdmin')
        @if($roles != null)
            <div class="row">
                <table class="col-12 table table-striped table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th colspan="2" class="text-center">Projekto dalyvių rolės</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    @foreach ($roles as $role)
                        <tr>
                            <td class="w-50 text-break">{{ $role->pavadinimas }}</td>
                            <td class="w-50">
                                <a class="btn btn-warning w-100" href="{{ route('roles.edit', $role) }}">Keisti pavadinimą</a>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2"></div>
                                        <a class="col-8 btn btn-success w-100" href="{{ route('roles.create') }}"> Nauja rolė</a>
                                    <div class="col-2"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                {!! $roles->links() !!}
            </div>
        @else
        <div class="col-12">Nėra nei vienos rolės</div>
        <div class="col-12">
            <div class="row">
                <div class="col-2"></div>
                    <a class="col-8 btn btn-success w-100" href="{{ route('roles.create') }}"> Nauja rolė</a>
                <div class="col-2"></div>
            </div>
        </div>
        @endif
    @endcan
@endsection

@section('meniu')

<div class="col-12">
    <div class="card">
        @can('isAdmin')
        <div class="card-header">
            <div class="row">
                <div class="col-12 text-center">
                    <div class=" mt-3"><h4>Bendra informacija</h4></div>
                    <p><strong>Bendras projektų skaičius: </strong><i> {{ $projektuSkaicius }} </i></p>
                    <p><strong>Pradėtų projektų skaičius: </strong><i> {{ $pradetuProjektuSkaicius }} </i></p>
                    <p><strong>Užbaigtų projektų skaičius: </strong><i> {{ $uzbaigtuProjektuSkaicius }} </i></p>
                    <p><strong>Aštauktų projektų skaičius: </strong><i> {{ $atsauktuProjektuSkaicius }} </i></p>
                </div>
            </div>
            <div class="h-50">
                <a class="btn btn-success w-100" href="{{ route('projektai.create') }}">Naujas projektas</a>
            </div>
        </div>
        @endcan
        @cannot('isAdmin')
            <div class="card-header">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class=" mt-3"><h4>Bendra informacija apie priskirtus projektus</h4></div>
                        <p><strong>Priskirtų projektų skaičius: </strong><i> {{ $projektuSkaicius }} </i></p>
                        <p><strong>Pradėtų projektų skaičius: </strong><i> {{ $pradetuProjektuSkaicius }} </i></p>
                        <p><strong>Užbaigtų projektų skaičius: </strong><i> {{ $uzbaigtuProjektuSkaicius }} </i></p>
                    </div>
                </div>
            </div>
        @endcan
        @if(session('status'))
        <div class="alert alert-success mb-1 mt-1">
            {{ session('status') }}
        </div>
        @endif
        <div class="card-body">
            <a class="btn btn-info w-100" href="{{ route('projektai.index') }}">Projektų sąrašas</a>
            <div class="text-center mt-4"><h3>Projektų sąrašas pagal datą</h3></div>
            <form class="col-12" action="{{ route('projektai.paieska') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="form-group text-center">
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
</div>
@endsection

@section('projektai')
<div class="row">
    <table class="col-12 table table-striped table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th colspan="2" class="text-center">Naujausi priskirti projektai</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($projektai as $projektas)
            <tr>
                <td class="w-75 text-break">{{ $projektas->pavadinimas }}</td>
                <td class="w-25">
                    <a class="btn btn-info" href="{{ route('projektai.show', $projektas) }}">Plačiau</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $projektai->links() !!}
</div>
@endsection