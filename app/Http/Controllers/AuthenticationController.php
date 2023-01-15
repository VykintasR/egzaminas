<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Darbuotojas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    function index()
    {
        return view('login');
    }

    function registration()
    {
        return view('registration');
    }

    function validate_registration(Request $request)
    {
        $request->validate([
            'vardas'            =>   'required',
            'pavarde'           =>   'required',
            'telefonas'         =>   'required',
            'email'             =>   'required|email|unique:darbuotojas',
            'password'          =>   'required|min:8'
        ]);

        $data = $request->all();

        Darbuotojas::create([
            'vardas'            =>  $data['vardas'],
            'pavarde'           =>  $data['pavarde'],
            'email'             =>  $data['email'],
            'telefonas'         =>  $data['telefonas'],
            'isAdmin'           =>  0,
            'password' => Hash::make($data['password'])
        ]);

        return redirect('login')->with('success', 'Sėkmingai prisiregistruota, dabar galite prisijungti.');
    }

    function validate_login(Request $request)
    {
        $request->validate([
            'email' =>  'required|email',
            'password'  =>  'required'
        ]);

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials))
        {
            return redirect('home');
        }

        return redirect('login')->with('success', 'Prisijungimo duomenys neteisingi.');
    }

    function dashboard()
    {
        if(Auth::check())
        {
            return view('home');
        }

        return redirect('login')->with('success', 'Neturima priegos.');
    }

    function logout()
    {
        Session::flush();

        Auth::logout();

        return Redirect('login');
    }
}
