<?php

namespace App\Http\Controllers;

use App\Models\ProjektoDalyvis;
use App\Models\Projektas;
use App\Models\Role;
use App\Models\Darbuotojas;
use Illuminate\Http\Request;
use App\Helpers\Funkcijos;
use DateTime;

class ProjektoDalyvisController extends Controller
{

    public function __construct()
    {
        $this->middleware('AdminAccess')->except(['index']);
    }

    public function index(Projektas $projektas)
    {
        $dalyviai = ProjektoDalyvis::select('projekto_dalyvis.*', 'role.pavadinimas as role')
                                    ->join('role', 'role.id', '=', 'projekto_dalyvis.roles_id')
                                    ->where('projekto_id','=', $projektas->id) ->orderBy('id')->paginate(9);

        foreach($dalyviai as $dalyvis)
        {
            $dalyv_prad = new DateTime($dalyvis->dalyvavimo_pradzios_data);
            $dalyv_pab = new DateTime($dalyvis->dalyvavimo_pabaigos_data);
            $dalyvis->dalyvavimo_trukme = Funkcijos::ApskaiciuotiTrukme($dalyv_prad, $dalyv_pab);
        }
        return view('dalyviai.index', compact('dalyviai', 'projektas'));
    }

    public function create(Projektas $projektas)
    {  
        $pradzia = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);
        $pabaiga = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);
        $roles = Role::all();
        $darbuotojai = Darbuotojas::all();
        return view('dalyviai.create', compact('projektas', 'pradzia', 'pabaiga', 'roles', 'darbuotojai'));
    }

    public function store(Request $request, Projektas $projektas)
    {
        $pradziosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);
        $pabaigosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);

        if($request->darbuotojas == null)
        {
            $request->validate([
                'pavadinimas'   => 'required|string|max:50',
                'role'          => 'required|integer|numeric|exists:role,id',
                'pradzia'       => 'required|date|before_or_equal:pabaiga|after_or_equal:'.$pradziosRiba,
                'pabaiga'       => 'required|date|after_or_equal:pradzia|before_or_equal:'.$pabaigosRiba,
            ]);
        }
        else
        {
            $request->validate([
            'pavadinimas'   => 'required|string|max:50',
            'role'          => 'required|integer|numeric|exists:role,id',
            'darbuotojas'   => 'required|integer|numeric|exists:darbuotojas,id',
            'pradzia'       => 'required|date|date_equals:'.$pradziosRiba,
            'pabaiga'       => 'required|date|date_equals:'.$pabaigosRiba,
            ]);
        }
        

        if(self::arProjektoDarbuotojas($projektas,$request->darbuotojas))
        {
            return redirect()->back()->withInput()->withErrors(['jauPridetas'=>'Pasirinktas darbuotojas jau itrauktas kaip projekto dalyvis']);
        }
        
        ProjektoDalyvis::create([
            'projekto_id' => $projektas->id,
            'roles_id' => $request->role,
            'darbuotojo_id' => $request->darbuotojas,
            'pavadinimas' => $request->pavadinimas,
            'dalyvavimo_pradzios_data' => $request->pradzia,
            'dalyvavimo_pabaigos_data' => $request->pabaiga
        ]);

        return redirect()->route('projektas.dalyviai.index', $projektas)->with('success','Dalyvis sėkmingai priskirtas.');
    }

    public function edit(Projektas $projektas, ProjektoDalyvis $dalyvis)
    {
        $pasirinktaRole = Role::select('id', 'pavadinimas')->where('id','=',$dalyvis->roles_id)->first();
        $roles = Role::all()->except($dalyvis->roles_id);
        $darbuotojai = Darbuotojas::all();
        $pradzia = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);
        $pabaiga = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);
        return view('dalyviai.edit', compact('projektas','dalyvis','pradzia','pabaiga','roles','pasirinktaRole','darbuotojai'));
    }

    public function update(Request $request, Projektas $projektas, ProjektoDalyvis $dalyvis)
    {
        $pradziosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pradzios_data, $projektas->reali_pradzios_data);
        $pabaigosRiba = Funkcijos::RealiArPlanuojamaData($projektas->planuojama_pabaigos_data, $projektas->reali_pabaigos_data);

        if($request->darbuotojas == null)
        {
            $request->validate([
                'pavadinimas'   => 'required|string|max:50',
                'role'          => 'required|integer|numeric|exists:role,id',
                'pradzia'       => 'required|date|before_or_equal:pabaiga|after_or_equal:'.$pradziosRiba,
                'pabaiga'       => 'required|date|after_or_equal:pradzia|before_or_equal:'.$pabaigosRiba,
            ]);
        }
        else
        {
            $request->validate([
            'pavadinimas'   => 'required|string|max:50',
            'role'          => 'required|integer|numeric|exists:role,id',
            'darbuotojas'   => 'required|integer|numeric|exists:darbuotojas,id',
            'pradzia'       => 'required|date|before_or_equal:pabaiga|after_or_equal:'.$pradziosRiba,
            'pabaiga'       => 'required|date|after_or_equal:pradzia|before_or_equal:'.$pabaigosRiba,
            ]);
        }

        $dalyvis->update([
            'roles_id' => $request->role,
            'darbuotojo_id' => $request->darbuotojas,
            'pavadinimas' => $request->pavadinimas,
            'dalyvavimo_pradzios_data' => $request->pradzia,
            'dalyvavimo_pabaigos_data' => $request->pabaiga
        ]);

        return redirect()->route('projektas.dalyviai.index', $projektas)->with('success','Dalyvio informacija sėkmingai pakoreguota.');
    }

    public function destroy(Projektas $projektas, ProjektoDalyvis $dalyvis)
    {
        $dalyvis->delete();
        return redirect()->back()->with('success','Dalyvis sėkmingai išbrauktas.');
    }

    //Dalyviu id
    public static function projektoDalyviai(Projektas $projektas)
    {
       return ProjektoDalyvis::select('id')->where('projekto_id', $projektas->id)->whereNull('darbuotojo_id')->get();
    }

    //Darbuotoju id
    public static function projektoDarbuotojai(Projektas $projektas)
    {
       return ProjektoDalyvis::select('id')->where('projekto_id', $projektas->id)->whereNotNull('darbuotojo_id')->get();
    }

    public static function arProjektoDarbuotojas(Projektas $projektas, $darbuotojo_id)
    {
        return ProjektoDalyvis::select()->where('darbuotojo_id',$darbuotojo_id)->where('projekto_id',$projektas->id)->exists();
    }

    //Darbuotoju id, vardas, pavarde
    public static function projektoDarbuotojuInformacija(Projektas $projektas)
    {
       return ProjektoDalyvis::select('darbuotojas.id','darbuotojas.vardas','darbuotojas.pavarde')
                                ->join('darbuotojas', 'darbuotojas.id', '=', 'projekto_dalyvis.darbuotojo_id')
                                ->where('projekto_id', $projektas->id)->whereNotNull('darbuotojo_id')->get();
    }

    public static function veliausiaVisuDalyviuPabaigosData(Projektas $projektas)
    {
        return ProjektoDalyvis::select('dalyvavimo_pabaigos_data')->where('projekto_id','=', $projektas->id)->max('dalyvavimo_pabaigos_data');
    }
}
