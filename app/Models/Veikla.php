<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veikla extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'projekto_id',
        'pavadinimas',
        'aprasymas',
        'prioritetas',
        'planuojama_pradzios_data',
        'planuojama_pabaigos_data',
        'planuojamas_biudzetas',
        'reali_pradzios_data',
        'reali_pabaigos_data',
        'realus_biudzetas'
    ];

    protected $table = 'veikla';
}
