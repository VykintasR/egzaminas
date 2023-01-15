<?php

namespace App\Http\Controllers;

use App\Models\DarbuotojuVeiklos;
use App\Models\Darbuotojas;
use App\Models\Veikla;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DarbuotojuVeiklosController extends Controller
{
    public function __construct()
    {
        $this->middleware('AdminAccess');
    }

    public static function store(Veikla $veikla, $darbuotojo_id)
    {
        DarbuotojuVeiklos::create([
            'darbuotojo_id' => $darbuotojo_id,
            'veiklos_id' => $veikla->id,
            'paskyrimo_data' => Carbon::Now(),
        ]);
    }

    public static function veiklosDarbuotojuInformacija(Veikla $veikla)
    {
        return DarbuotojuVeiklos::select('darbuotojas.id','darbuotojas.vardas', 'darbuotojas.pavarde')
                                ->join('darbuotojas','darbuotojas.id','=','darbuotojo_id')
                                ->where('veiklos_id', $veikla->id)->get();
    }

    public static function arPriskirtas(Veikla $veikla, $darbuotojo_id)
    {
        return DarbuotojuVeiklos::select()->where('darbuotojo_id',$darbuotojo_id)->where('veiklos_id',$veikla->id)->exists();
    }


    public static function istrintiViena(Veikla $veikla, $darbuotojo_id)
    {
        DarbuotojuVeiklos::select()->where('veiklos_id', $veikla->id)->where('darbuotojo_id',$darbuotojo_id)->delete();
    }

    public static function destroy(Veikla $veikla)
    {
        DarbuotojuVeiklos::select()->where('veiklos_id', $veikla->id)->delete();
    }

    public static function darbuotojuSkaicius(Veikla $veikla)
    {

        return DarbuotojuVeiklos::select()->where('veiklos_id', $veikla->id)->count();
    }
}
