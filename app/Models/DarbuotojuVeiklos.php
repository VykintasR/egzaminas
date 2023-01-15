<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DarbuotojuVeiklos extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paskyrimo_data',
        'projekto_id',
        'veiklos_id',
        'darbuotojo_id',
    ];

    protected $table = 'darbuotoju_veiklos';
}
