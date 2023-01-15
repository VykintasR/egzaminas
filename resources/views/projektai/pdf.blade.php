<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body class="bg-light">
        <div class="row">
            <div class="col-lg-12 text-center margin-tb">
                <h2>Projektų sąrašas</h2>
            </div>
        </div>
        @if(!$projektai->isEmpty())
        <table class="table table-bordered">
            @foreach ($projektai as $projektas)
                <tr>
                    <th>Projekto pavadinimas</th>
                    <th>Projekto planuojama pradžios data</th>
                </tr>
                <tr>
                    <td>{{ $projektas->pavadinimas }}</td>
                    <td>{{ $projektas->planuojama_pradzios_data }}</td>
                </tr>
                <tr>
                    <th class="text-center" colspan="2">Projekto aprašymas</th>
                </tr>
                <tr>
                    <td colspan="2">{{ $projektas->aprasymas }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @else
            <p class="text-center">Nėra sukurtų projektų.</p>
        @endif
    </body>
</html>
