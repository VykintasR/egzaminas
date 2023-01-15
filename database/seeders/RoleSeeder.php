<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([ 'pavadinimas' => 'Projekto vadovas']);
        Role::create([ 'pavadinimas' => 'Projekto darbuotojas']);
        Role::create([ 'pavadinimas' => 'Projekto klientas']);
        Role::create([ 'pavadinimas' => 'Projekto partneris']);
    }
}
