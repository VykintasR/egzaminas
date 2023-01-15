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
                grid-template-rows: 0.1fr 0.2fr 1fr 0.2fr 0.1fr;
                min-height:100vh;
            }
        </style>
    </head>
    <body class="bg-light">
        <nav class="col-lg-12 navbar navbar-expand-lg navbar-light bg-info border border-dangers">
            <div class="col-lg-12 container-fluid">
                <div class="display-4 m-4 pull-left">Project Assistant</div>
            </div>
        </nav>

        <div></div>
        <div class="row text-center container-fluid">
            @if($message = Session::get('success'))
            <div class="col-12 alert alert-info">{{ $message }}</div>
            @endif
            <div class="col-4"> </div>
            <div class="col-4">
                <div class="row container-fluid">
                    <div class="col-12">
                        <div class="card">
                            <h5 class="card-header">Prisijungimas</h5>
                            <div class="card-body">
                                <form action="{{ route('login') }}" method="post">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <strong>El paštas:</strong>
                                        <input type="email" name="email" class="form-control" placeholder="el paštas" requireds/>
                                        @if($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group mb-3">
                                        <strong>Slaptažodis:</strong>
                                        <input type="password" name="password" class="form-control" placeholder="Slaptažodis" required/>
                                        @if($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>

                                    <div class="row form-group container-fluid">
                                        <div class="col-12">
                                            <input id="remember_me" type="checkbox" name="remember">
                                            <span class=" text-sm text-dark">Prisiminti mane</span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <!---<div class="col-6">
                                            @if (Route::has('password.request'))
                                                <a class="underline text-sm text-dark" href="{{ route('password.request') }}">
                                                    Pamiršote slaptažodį
                                                </a>
                                            @endif
                                        </div>--->
                                        <div class="col-12 mx-auto">
                                        <button type="submit" class="btn btn-dark btn-block">Prisijungti</button>
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4"> </div>
        </div>

        <div></div>

        <footer class="col-12 bg-info text-center p-4">
                &copy; 2022 TaskMaster - All Rights Reserved.
        </footer>
    </body>
</html>