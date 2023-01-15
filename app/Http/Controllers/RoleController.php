<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function create()
    {
         return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pavadinimas' => 'required|string|max:50|unique:role'
        ]);
        
        Role::create($request->all());

        return redirect()->route('pagrindinis')->with('success','Rolė sėkmingai sukurta.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'pavadinimas' => 'required|string|max:50|unique:role'
        ]);
        
        $role->update($request->all());

        return redirect()->route('home')->with('success','Rolės pavadinimas sėkmingai pakoreguotas.');
    }
}
