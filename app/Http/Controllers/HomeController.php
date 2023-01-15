<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projektas;
use App\Models\Role;


class HomeController extends Controller
{
    public function index()
    {
        if (Auth()->user()->isAdmin == 1)
        {
            $projektai = Projektas::orderBy('id','desc')->paginate(7);
            $roles = Role::orderBy('id','desc')->paginate(7);
            $projektuSkaicius = Projektas::select('id')->count();
            $pradetuProjektuSkaicius = Projektas::select('id')->whereNotNull('reali_pradzios_data')->whereNull('reali_pabaigos_data')->count();
            $atsauktuProjektuSkaicius = Projektas::select('id')->whereNotNull('deleted_at')->withTrashed()->count();
            $uzbaigtuProjektuSkaicius = Projektas::select('id')->whereNotNull('reali_pabaigos_data')->count();

            return view('home', compact('projektai', 'roles', 'projektuSkaicius', 'atsauktuProjektuSkaicius', 'uzbaigtuProjektuSkaicius',
                                    'pradetuProjektuSkaicius'));   
        }
        else
        {
            $projektai = Projektas::select('projektas.*')->orderBy('projektas.id','desc')
            ->join('projekto_dalyvis','projektas.id','projekto_dalyvis.projekto_id')
            ->where('darbuotojo_id',Auth()->user()->id)->paginate(7);

            $projektuSkaicius = $projektai->count();
            $pradetuProjektuSkaicius = Projektas::select('id')->whereNotNull('reali_pradzios_data')->whereNull('reali_pabaigos_data')->count();
            $uzbaigtuProjektuSkaicius = Projektas::select('id')->whereNotNull('reali_pabaigos_data')->count();

            return view('home', compact('projektai', 'projektuSkaicius', 'uzbaigtuProjektuSkaicius','pradetuProjektuSkaicius'));    
        }  
    }
}
