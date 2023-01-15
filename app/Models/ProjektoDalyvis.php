<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjektoDalyvis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pavadinimas',
        'projekto_id',
        'darbuotojo_id',
        'roles_id',
        'dalyvavimo_pradzios_data',
        'dalyvavimo_pabaigos_data'
    ];
    protected $table = 'projekto_dalyvis';
}
