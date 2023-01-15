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
                grid-template-rows: auto 1fr auto;
                min-height:100vh;
            }
        </style>
    </head>
    <body class="bg-light">
        @section('navigacija')
        <nav class="col-lg-12 navbar navbar-expand-lg navbar-light bg-info border">
            <div class="col-lg-12 container-fluid">
            <div class="display-4 m-4 pull-left">Project Assistant</div>
                <ul class="navbar-nav h3">
                    @yield('nuorodos')
                </ul>
            </div>
        </nav>
        @show
        
        
        <section class="container-fluid">
            @yield('turinys')
        </section>

        @section('footer')
        <footer class="col-12 bg-info text-center p-4">
            &copy; 2022 Vykintas Rimeika - All Rights Reserved.
        </footer>
        @show
    </body>
</html>