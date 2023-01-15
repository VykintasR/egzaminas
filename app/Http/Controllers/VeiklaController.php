<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veikla;
use App\Models\Projektas;
use App\Helpers\Funkcijos;
use DateTime;

class VeiklaController extends Controller
{
    public function __construct()
    {
        $this->middleware('AdminAccess')->except(['index', 'show']);
    }
    
    public function index(Projektas $projektas)
    {
        if (Auth()->user()->isAdmin == 1)
        {
            $veiklos = Veikla::select('veikla.*')->where('projekto_id','=', $projektas->id)->orderBy('prioritetas','desc')->paginate(9);
        }
        else
        {
            $veiklos = Veikla::select('veikla.*')->join('darbuotoju_veiklos','veikla.id','darbuotoju_veiklos.veiklos_id')
                                                ->where('veikla.projekto_id','=', $projektas->id)->where('darbuotojo_id',Auth()->user()->id)
                                                ->orderBy('prioritetas','desc')->paginate(9);
        }
        

        foreach($veiklos as $veikla)
        {
            $pradzia = Funkcijos::RealiArPlanuojamaData($veikla->planuojama_pradzios_data, $veikla->reali_pradzios_data);
            $pabaiga = Funkcijos::RealiArPlanuojamaData($veikla->planuojama_pabaigos_data, $veikla->reali_pabaigos_data);
            $veikla->trukme = Funkcijos::ApskaiciuotiTrukme(new DateTime($pradzia), new DateTime($pabaiga));

            $biudzetas = Funkcijos::RealusArPlanuojamasBiudzetas($veikla);
            $biudzetas = number_format($biudzetas/100,2,',', '');
            $veikla->biudzetas = $biudzetas;
        }
        return view('veiklos.index', compact('veiklos', 'projektas'));
    }

    public function create(Projektas $projektas)
    {
        $darbuotojai = ProjektoDalyvisController::projektoDarbuotojuInformacija($projektas);

        $pradzia = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);
        $pabaiga = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);

        $laisvasBiudzetas = $projektas->projekto_biudzetas - self::veikluBiudzetas($projektas);
        $laisvasBiudzetas  = number_format($laisvasBiudzetas /100,2,'.', '');

        return view('veiklos.create', compact('projektas', 'pradzia', 'pabaiga', 'darbuotojai', 'laisvasBiudzetas'));
    }

    public function store(Request $request, Projektas $projektas)
    {
        $pradziosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);
        $pabaigosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);

        $laisvasBiudzetas = $projektas->projekto_biudzetas - self::veikluBiudzetas($projektas);
        $laisvasBiudzetas  = number_format($laisvasBiudzetas /100,2,'.', '');

        $request->validate([
            'pavadinimas'   => 'required|string|max:50',
            'aprasymas'     => 'nullable|string|max:1000',
            'darbuotojas'   => 'required|integer|numeric|exists:darbuotojas,id',
            'prioritetas'   => 'required|integer|numeric|between:1,100',
            'pradzia'       => 'required|date|before_or_equal:pabaiga|after_or_equal:'.$pradziosRiba,
            'pabaiga'       => 'required|date|after_or_equal:pradzia|before_or_equal:'.$pabaigosRiba,
            'biudzetas'     => 'required|gte:0|lte:'.$laisvasBiudzetas .'|regex:/^\d*(\.\d{1,2})?$/'
        ]);

        $veikla = Veikla::create([
            'projekto_id' => $projektas->id,
            'darbuotojo_id' => $request->darbuotojas,
            'pavadinimas' => $request->pavadinimas,
            'aprasymas' => $request->aprasymas,
            'prioritetas' => $request->prioritetas,
            'planuojama_pradzios_data' => $request->pradzia,
            'planuojama_pabaigos_data' => $request->pabaiga,
            'planuojamas_biudzetas' => $request->biudzetas * 100,
        ]);

        DarbuotojuVeiklosController::store($veikla, $request->darbuotojas);

        return redirect()->route('projektas.veiklos.index', $projektas)->with('success','Veikla sėkmingai sukurta.');
    }

    public function show(Projektas $projektas, Veikla $veikla)
    {
        $nepriskirtasBiudzetas = self::nepriskirtasBiudzetas($projektas, $veikla);
        $nepriskirtasBiudzetas = number_format($nepriskirtasBiudzetas/100,2,'.', '');

        $veikla->planuojamas_biudzetas = number_format( $veikla->planuojamas_biudzetas/100,2,',', '');

        if($veikla->realus_biudzetas != null)
        {
            $veikla->realus_biudzetas = number_format($veikla->realus_biudzetas/100,2,',', '');
        }
        
        $realiTrukme = "";
        if(!empty($veikla->reali_pabaigos_data))
        {
            $pradzia = new DateTime($veikla->reali_pradzios_data);
            $pabaiga = new DateTime($veikla->reali_pabaigos_data); 
            $realiTrukme = Funkcijos::ApskaiciuotiTrukme($pradzia, $pabaiga);
        }

        $pradzia = new DateTime($veikla->planuojama_pradzios_data);
        $pabaiga = new DateTime($veikla->planuojama_pabaigos_data);
        $trukme = Funkcijos::ApskaiciuotiTrukme($pradzia, $pabaiga);

        $veiklosDarbuotojai = DarbuotojuVeiklosController::veiklosDarbuotojuInformacija($veikla);
        $projektoDarbuotojai = ProjektoDalyvisController::projektoDarbuotojuInformacija($projektas);
        $nepriskirtiDarbuotojai = $projektoDarbuotojai->diff($veiklosDarbuotojai);

        //Realios datos fiksavimo apribojimas
        $pradziosRiba = Funkcijos::RealiArPlanuojamaData($veikla->planuojama_pradzios_data, $veikla->reali_pradzios_data);
        $pabaigosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);

        return view('veiklos.show', compact('projektas', 'veikla','trukme', 'realiTrukme', 'veiklosDarbuotojai', 'pradziosRiba',
                                            'pabaigosRiba','nepriskirtiDarbuotojai', 'nepriskirtasBiudzetas'));
    }

    public function edit(Projektas $projektas, Veikla $veikla)
    {
        $veikla->planuojamas_biudzetas = number_format( $veikla->planuojamas_biudzetas/100,2,'.', '');
        $laisvasBiudzetas =  self::nepriskirtasBiudzetas($projektas, $veikla);
        $laisvasBiudzetas  = number_format($laisvasBiudzetas /100,2,'.', '');

        $pradzia = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);
        $pabaiga = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);

        return view('veiklos.edit', compact('projektas', 'veikla', 'pradzia', 'pabaiga', 'laisvasBiudzetas'));
    }

    public function update(Request $request, Projektas $projektas, Veikla $veikla)
    {
        $pradziosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);
        $pabaigosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);

        $laisvasBiudzetas = self::nepriskirtasBiudzetas($projektas, $veikla);
        $laisvasBiudzetas  = number_format($laisvasBiudzetas /100,2,'.', '');
        
        $request->validate([
            'pavadinimas'   => 'required|string|max:50',
            'aprasymas'     => 'nullable|string|max:1000',
            'prioritetas'   => 'required|integer|numeric|between:1,100',
            'pradzia'       => 'required|date|before_or_equal:pabaiga|after_or_equal:'.$pradziosRiba,
            'pabaiga'       => 'required|date|after_or_equal:pradzia|before_or_equal:'.$pabaigosRiba,
            'biudzetas'     => 'required|gte:0|lte:'.$laisvasBiudzetas .'|regex:/^\d*(\.\d{1,2})?$/'
        ]);

        
        $veikla->update([
            'pavadinimas' => $request->pavadinimas,
            'aprasymas' => $request->aprasymas,
            'prioritetas' => $request->prioritetas,
            'planuojama_pradzios_data' => $request->pradzia,
            'planuojama_pabaigos_data' => $request->pabaiga,
            'planuojamas_biudzetas' => $request->biudzetas * 100
        ]);

        return redirect()->route('projektas.veiklos.index', $projektas)->with('success','Veiklos informacija sėkmingai pakoreguota.');
    }

    public function destroy(Projektas $projektas, Veikla $veikla)
    {
        DarbuotojuVeiklosController::destroy($veikla);
        $veikla->delete();
        return redirect()->route('projektas.veiklos.index', $projektas)->with('success','Veikla sėkmingai išbraukta.');
    }

    public function fiksuotiPradzia(Request $request, Projektas $projektas, Veikla $veikla)
    {   
        $pradziosRiba = Funkcijos::RealiArPlanuojamaData($veikla->planuojama_pradzios_data, $veikla->reali_pradzios_data);
        $pabaigosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);

        $request->validate([
            'pradzios_data' => 'required|date|after_or_equal:'.$pradziosRiba.'|before_or_equal:'.$pabaigosRiba
        ]);

        $veikla->reali_pradzios_data = $request->pradzios_data;
        $veikla->save();

        return redirect()->route('projektas.veiklos.show',['projektas' => $projektas, 'veikla' => $veikla])->with('success','Veiklos vykdymo pradžios data nustatyta.');
    }

    public function anuliuotiPradzia(Request $request, Projektas $projektas, Veikla $veikla)
    {   
        $veikla->reali_pradzios_data = null;
        $veikla->save();

        return redirect()->route('projektas.veiklos.show', ['projektas' => $projektas, 'veikla' => $veikla])->with('success','Projekto vykdymo pradžios data anuliuota.');
    }

    public function fiksuotiPabaiga(Request $request, Projektas $projektas, Veikla $veikla)
    {   
        $pradziosRiba = Funkcijos::RealiArPlanuojamaData($veikla->planuojama_pradzios_data, $veikla->reali_pradzios_data);
        $pabaigosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);

        $request->validate([
            'pabaigos_data' => 'required|date|after_or_equal:'.$pradziosRiba.'|before_or_equal:'.$pabaigosRiba
        ]);
    
        $veikla->reali_pabaigos_data = $request->pabaigos_data; 
        $veikla->save();

        return redirect()->route('projektas.veiklos.show', ['projektas' => $projektas, 'veikla' => $veikla])->with('success','Projekto vykdymo pabaigos data nustatyta.');
    }

    public function anuliuotiPabaiga(Request $request, Projektas $projektas, Veikla $veikla)
    {   
        $veikla->reali_pabaigos_data = null;
        $veikla->save();

        return redirect()->route('projektas.veiklos.show', ['projektas' => $projektas, 'veikla' => $veikla])->with('success','Projekto vykdymo pabaigos data anuliuota.');
    }

    public function fiksuotiBiudzeta(Request $request, Projektas $projektas, Veikla $veikla)
    {   
        $laisvasBiudzetas = self::nepriskirtasBiudzetas($projektas, $veikla);
        $laisvasBiudzetas  = number_format($laisvasBiudzetas /100,2,'.', '');

        $request->validate([
            'biudzetas' => 'required|gte:0|lte:'.$laisvasBiudzetas .'|regex:/^\d*(\.\d{1,2})?$/'
        ]);

        $veikla->realus_biudzetas = $request->biudzetas * 100; 
        $veikla->save();

        return redirect()->route('projektas.veiklos.show', ['projektas' => $projektas, 'veikla' => $veikla])->with('success','Projekto realus biudžetas nustatytass.');
    }

    public function anuliuotiBiudzeta(Request $request, Projektas $projektas, Veikla $veikla)
    {   
        $veikla->realus_biudzetas = null;
        $veikla->save();

        return redirect()->route('projektas.veiklos.show', ['projektas' => $projektas, 'veikla' => $veikla])->with('success','Projekto vykdymo pabaigos data anuliuota.');
    }

    private function nepriskirtasBiudzetas(Projektas $projektas, Veikla $veikla)
    {
        $veiklos = Veikla::select()->where('projekto_id',$projektas->id)->whereNot('id',$veikla->id)->get();

        $priskirtasBiudzetas = 0;

        foreach($veiklos as $veik)
        {
            $priskirtasBiudzetas += Funkcijos::RealusArPlanuojamasBiudzetas($veik);
        }

        return $projektas->projekto_biudzetas - $priskirtasBiudzetas;
    }

    public static function veikluBiudzetas(Projektas $projektas)
    {
        $veiklos = Veikla::select()->where('projekto_id',$projektas->id)->get();

        $biudzetas = 0;

        foreach($veiklos as $veikla)
        {
                $biudzetas += Funkcijos::RealusArPlanuojamasBiudzetas($veikla);
        }

        return $biudzetas;
    }

    public function pridetiDarbuotoja(Request $request, Projektas $projektas, Veikla $veikla)
    {
        $request->validate([
            'nepriskirtasDarbuotojas' => 'required|integer|numeric|exists:darbuotojas,id',
        ]);

        $projektoDalyvis = ProjektoDalyvisController::arProjektoDarbuotojas($projektas, $request->nepriskirtasDarbuotojas);

        if($projektoDalyvis)
        {
            $yra = DarbuotojuVeiklosController::arPriskirtas($veikla, $request->nepriskirtasDarbuotojas);
            if($yra)
            {
                return redirect()->back()->withInput()
                ->withErrors(['jauPriskirtas'=>'Pasirinktas darbuotojas jau priskirtas šiai veiklai.']);
            }
            else
            {
                DarbuotojuVeiklosController::store($veikla, $request->nepriskirtasDarbuotojas);
                return redirect()->back()->with('success','Darbuotojas sėkmingai priskirtas.');
            }
        }
        else
        {
            return redirect()->back()->withInput()
                ->withErrors(['pridedamasNeDalyvis'=>'Pasirinktas darbuotojas nėra projekto dalyvis']);
        }
    }

    public function ismestiDarbuotoja(Request $request, Projektas $projektas, Veikla $veikla)
    {
        $request->validate([
            'veiklosDarbuotojas' => 'required|integer|numeric|exists:darbuotojas,id',
        ]);

        $projektoDalyvis = ProjektoDalyvisController::arProjektoDarbuotojas($projektas, $request->veiklosDarbuotojas);

        if($projektoDalyvis)
        {
            $darbuotojuSkaicius = DarbuotojuVeiklosController::darbuotojuSkaicius($veikla);

            if($darbuotojuSkaicius <= 1)
            {
                return redirect()->back()->withInput()
                ->withErrors(['paskutinisDarbuotojas'=>'Negalima palikti veiklos be darbuotojo.']);
            }
            else
            {
                $yra = DarbuotojuVeiklosController::arPriskirtas($veikla, $request->veiklosDarbuotojas);

                if($yra)
                {
                    DarbuotojuVeiklosController::istrintiViena($veikla, $request->veiklosDarbuotojas);
                    return redirect()->back()->with('success','Darbuotojas sėkmingai išbrauktas.');
                }
                else
                {
                    return redirect()->back()->withInput()
                    ->withErrors(['negalimasIsmetimas'=>'Pasirinktas darbuotojas nepriskirtas veiklai.']);
                }
            }          
        }
        else
        {
            return redirect()->back()->withInput()
                ->withErrors(['ismetamasNeDalyvis'=>'Pasirinktas darbuotojas nėra projekto dalyvis.']);
        }
    }

    public static function veliausiaVisuVeikluPabaigosData(Projektas $projektas)
    {
        $planuojama = Veikla::select('planuojama_pabaigos_data')->where('projekto_id','=', $projektas->id)->max('planuojama_pabaigos_data');
        $reali = Veikla::select('reali_pabaigos_data')->where('projekto_id','=', $projektas->id)->max('planuojama_pabaigos_data');

        return $planuojama < $reali ? $reali : $planuojama;
    }
}
