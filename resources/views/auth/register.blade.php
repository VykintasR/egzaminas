<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body
            {
                display:grid;
                grid-template-rows: auto 0.1fr 1fr 0.1fr auto;
                min-height:100vh;
            }
        </style>
    </head>
    <body class="bg-light">
        <nav class="col-lg-12 navbar navbar-expand-lg navbar-light bg-info border border-dangers">
            <div class="col-lg-12 container-fluid">
                <div class="display-4 m-4 pull-left">Projest Assistant</div>
            </div>
        </nav>

        <div></div>
        <div class="row text-center container-fluid">
            @if($message = Session::get('success'))
            <div class="col-12 alert alert-info">{{ $message }}</div>
            @endif
            <div class="col-4"> </div>
            <div class="col-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <h5 class="card-header">Registracija</h5>
                            <div class="card-body">
                                <form action="{{ route('register') }}" method="POST">
                                    @csrf
                                        <div class="form-group mb-2">
                                            <strong>Vardas:</strong>
                                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required/>
                                            @if($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group mb-2">
                                            <strong>Pavardė:</strong>
                                            <input type="text" name="pavarde" class="form-control" value="{{ old('pavarde') }}" required/>
                                            @if($errors->has('pavarde'))
                                                <span class="text-danger">{{ $errors->first('pavarde') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group mb-2">
                                            <strong>Mobilaus telefono numeris:</strong>
                                            <input type="text" name="telefonas" class="form-control" value="{{ old('telefonas') }}" required/>
                                            @if($errors->has('telefonas'))
                                                <span class="text-danger">{{ $errors->first('telefonas') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group mb-2">
                                            <strong>El. pašto adresas</strong>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required/>
                                            @if($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group mb-2">
                                            <strong>Slaptažodis:</strong>
                                            <input type="password" name="password" class="form-control" required/>
                                            @if($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group mb-2">
                                            <strong>Patvirtinti slaptažodį:</strong>
                                            <input type="password" name="password_confirmation" class="form-control" required/>
                                            @if($errors->has('password_confirmation'))
                                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-6 mt-4">
                                            <a class="underline text-sm text-dark" class="mb-3" href="{{ route('login') }}">
                                                Turite paskyrą?
                                            </a>
                                            </div>
                                            <div class="col-6 mt-3 mx-auto">
                                                <button type="submit" class="btn btn-dark btn-block">Registruotis</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="col-4"></div>
        </div>
    
        <div></div>
    
        <footer class="col-12 bg-info text-center p-4">
                &copy; 2022 TaskMaster - All Rights Reserved.
        </footer>
    </body>
</html>