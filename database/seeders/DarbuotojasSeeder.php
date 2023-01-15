<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Darbuotojas;
use Illuminate\Support\Facades\Hash;

class DarbuotojasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Darbuotojas::create([
            'vardas'            =>  'Kyle',
            'pavarde'           =>  'Stephenson',
            'email'             =>  'vadovas@gmail.com',
            'telefonas'         =>  '+370 686 76606',
            'isAdmin'           =>  1,
            'password'          => Hash::make('Vadovas123')
        ]);
        Darbuotojas::create([
            'vardas'            =>  'Timothy',
            'pavarde'           =>  'Vance',
            'email'             =>  'darbuotojas1@gmail.com',
            'telefonas'         =>  '+370 686 76616',
            'isAdmin'           =>  0,
            'password'          => Hash::make('Darbuotojas1')
        ]);

        Darbuotojas::create([
            'vardas'            =>  'William ',
            'pavarde'           =>  'Bradley',
            'email'             =>  'darbuotojas2@gmail.com',
            'telefonas'         =>  '+370 686 76626',
            'isAdmin'           =>  0,
            'password'          => Hash::make('Darbuotojas2')
        ]);
        Darbuotojas::create([
            'vardas'            =>  'Cory ',
            'pavarde'           =>  'Owen',
            'email'             =>  'darbuotojas3@gmail.com',
            'telefonas'         =>  '+370 686 76636',
            'isAdmin'           =>  0,
            'password'          => Hash::make('Darbuotojas3')
        ]);
    }
}
