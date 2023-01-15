<?php

namespace App\Http\Controllers;

use App\Helpers\Funkcijos;
use App\Models\Veikla;
use App\Models\Projektas;
use Illuminate\Http\Request;
use DateTime;
use PDF;

class ProjektasController extends Controller
{
    public function __construct()
    {
        $this->middleware('AdminAccess')->except(['index', 'show', 'indexFiltruotas']);
    }

    public function index()
    {
        if (Auth()->user()->isAdmin == 1)
        {
            $projektai = Projektas::orderBy('id','desc')->paginate(5);
        }
        else
        {
            $projektai = Projektas::select('projektas.*')->orderBy('projektas.id','desc')
            ->join('projekto_dalyvis','projektas.id','projekto_dalyvis.projekto_id')
            ->where('darbuotojo_id',Auth()->user()->id)->paginate(5);
        }
        return view('projektai.index', compact('projektai'));   
    }

    public function indexFiltruotas(Request $request)
    {
        $request->validate([
            'data' => 'required|date'
        ]);
        
        if (Auth()->user()->isAdmin == 1)
        {
            $projektai = Projektas::orderBy('id','desc')->where('planuojama_pradzios_data', '>=',$request->data)->paginate(5);
        }
        else
        {
            $projektai = Projektas::select('projektas.*')->orderBy('projektas.id','desc')
            ->join('projekto_dalyvis','projektas.id','projekto_dalyvis.projekto_id')
            ->where('planuojama_pradzios_data', '>=',$request->data)
            ->where('darbuotojo_id',Auth()->user()->id)->paginate(5);
        }
        return view('projektai.index', compact('projektai'));
    }

    public function create()
    {
        return view('projektai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pavadinimas' => 'required|string|max:150',
            'aprasymas' => 'required|string|max:3000',
            'pradzia' => 'required|date',
            'pabaiga' => 'required|date|after_or_equal:pradzia',
            'biudzetas' => 'required|gte:0|regex:/^\d*(\.\d{1,2})?$/'
        ]);
        
        Projektas::create([
            'pavadinimas' => $request->pavadinimas,
            'aprasymas' => $request->aprasymas,
            'planuojama_pradzios_data' => $request->pradzia,
            'planuojama_pabaigos_data' => $request->pabaiga,
            'projekto_biudzetas' => $request->biudzetas * 100 //biudžetas saugomas centais
        ]);

        return redirect()->route('projektai.index')->with('success','Projektas sėkmingai sukurtas.');
    }

    public function show(Projektas $projektas)
    {
        $biudzetasEur = number_format($projektas->projekto_biudzetas/100,2,',', '');
        $projektas->projekto_biudzetas = $biudzetasEur;

        $realiTrukme = "";
        if(!empty($projektas->reali_pabaigos_data))
        {
            $pradzia = new DateTime($projektas->reali_pradzios_data);
            $pabaiga = new DateTime($projektas->reali_pabaigos_data); 
            $realiTrukme = Funkcijos::ApskaiciuotiTrukme($pradzia, $pabaiga);
        }

        $pradzia = new DateTime($projektas->planuojama_pradzios_data);
        $pabaiga = new DateTime($projektas->planuojama_pabaigos_data);

        $trukme = Funkcijos::ApskaiciuotiTrukme($pradzia, $pabaiga);

        $veiklos = Veikla::where('projekto_id', $projektas->id)->get();
        $baigtosVeiklos = Veikla::where('projekto_id', $projektas->id)->whereNotNull('reali_pabaigos_data')->get();
        $nebaigtosVeiklos = Veikla::where('projekto_id', $projektas->id)->whereNull('reali_pabaigos_data')->get();

        $dalyviai = ProjektoDalyvisController::projektoDalyviai($projektas);
        $darbuotojai = ProjektoDalyvisController::projektoDarbuotojai($projektas);

        $veikluSkaicius = $veiklos->count();
        $baigtuVeikluSkaicius = $baigtosVeiklos->count();
        $nebaigtuVeikluSkaicius = $nebaigtosVeiklos->count();
        $dalyviuSkaicius = $dalyviai->count();
        $darbuotojuSkaicius = $darbuotojai->count();

        $projektoDarbuotojai = ProjektoDalyvisController::projektoDarbuotojuInformacija($projektas);

        //Realios datos fiksavimo apribojimas
        $datosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);

        return view('projektai.show', compact('projektas', 'realiTrukme', 'dalyviuSkaicius', 'darbuotojuSkaicius','veikluSkaicius',
                                              'trukme','baigtuVeikluSkaicius','nebaigtuVeikluSkaicius','datosRiba', 'projektoDarbuotojai'));
    }

    public function edit(Projektas $projektas)
    {
        $biudzetasEur = number_format($projektas->projekto_biudzetas/100,2,'.', '');
        $projektas->projekto_biudzetas = $biudzetasEur;
        return view('projektai.edit', compact('projektas'));
    }

    public function update(Request $request, Projektas $projektas)
    {
        $veikluBiudzetas = VeiklaController::veikluBiudzetas($projektas);
        $veikluBiudzetas = number_format($veikluBiudzetas/100,2,'.', '');

        //Projektas negali baigtis anksciau nei kuri nors jo veikla
        $veiklosPabaigosRiba = VeiklaController::veliausiaVisuVeikluPabaigosData($projektas);
        $dalyvioPabaigosRiba = ProjektoDalyvisController::veliausiaVisuDalyviuPabaigosData($projektas);
        
        $request->validate([
            'pavadinimas' => 'required|string|max:150',
            'aprasymas' => 'required|string|max:3000',
            'pradzia' => 'required|date',
            'pabaiga' => 'required|date|after_or_equal:pradzia|after_or_equal:'.$veiklosPabaigosRiba.'|after_or_equal:'.$dalyvioPabaigosRiba,
            'biudzetas' => 'required|gte:'.$veikluBiudzetas.'|regex:/^\d*(\.\d{1,2})?$/'
        ]);

        $projektas->update([
            'pavadinimas' => $request->pavadinimas,
            'aprasymas' => $request->aprasymas,
            'planuojama_pradzios_data' => $request->pradzia,
            'planuojama_pabaigos_data' => $request->pabaiga,
            'projekto_biudzetas' => $request->biudzetas * 100 //biudžetas saugomas centais
        ]);

        return redirect()->route('projektai.show', $projektas->id)->with('success','Projekto informacija sėkmingai atnaujinta.');
    }

    public function destroy(Projektas $projektas)
    {
        $projektas->delete();
        return redirect()->route('projektai.index')->with('success','Projektas sėkmingai atšauktas');
    }

    public function fiksuotiPradzia(Request $request, Projektas $projektas)
    {   
        $pradziosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);
        $pabaigosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);

        $request->validate([
            'pradzios_data' => 'required|date|after_or_equal:'.$pradziosRiba.'|before_or_equal:'.$pabaigosRiba,
        ]);

        $projektas->reali_pradzios_data = $request->pradzios_data;
        $projektas->save();

        return redirect()->route('projektai.show', $projektas)->with('success','Projekto vykdymo pradžios data nustatyta.');
    }

    public function anuliuotiPradzia(Request $request, Projektas $projektas)
    {   
        $projektas->reali_pradzios_data = null;
        $projektas->save();

        return redirect()->route('projektai.show', $projektas)->with('success','Projekto vykdymo pradžios data anuliuota.');
    }

    public function fiksuotiPabaiga(Request $request, Projektas $projektas)
    {   
        $datosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);
        //Projektas negali baigtis anksciau nei kuri nors jo veikla
        $veiklosPabaigosRiba = VeiklaController::veliausiaVisuVeikluPabaigosData($projektas);
        $dalyvioPabaigosRiba = ProjektoDalyvisController::veliausiaVisuDalyviuPabaigosData($projektas);

        $request->validate([
            'pabaigos_data' => 'required|date|after_or_equal:'.$datosRiba.'|after_or_equal:'.$veiklosPabaigosRiba.'|after_or_equal:'.$dalyvioPabaigosRiba,
        ]);   

        $projektas->reali_pabaigos_data = $request->pabaigos_data; 
        $projektas->save();

        return redirect()->route('projektai.show', $projektas)->with('success','Projekto vykdymo pabaigos data nustatyta.');
    }

    public function anuliuotiPabaiga(Request $request, Projektas $projektas)
    {   
        $projektas->reali_pabaigos_data = null;
        $projektas->save();

        return redirect()->route('projektai.show', $projektas)->with('success','Projekto vykdymo pabaigos data anuliuota.');
    }

        // Generate PDF
        public function createPDF() 
        {
            $projektai = Projektas::orderBy('id','desc')->paginate(5);
            // share data to view
            view()->share('projektai',$projektai);
            $pdf = PDF::loadView('projektai.pdf',compact('projektai'));
            // download PDF file with download method
            return $pdf->download('projektai.pdf');
        }
}
