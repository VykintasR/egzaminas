<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projektas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pavadinimas', 
        'aprasymas',
        'planuojama_pradzios_data',
        'planuojama_pabaigos_data',
        'reali_pradzios_data',
        'reali_pabaigos_data',
        'projekto_biudzetas'
    ];
    protected $table = 'projektas';
}
