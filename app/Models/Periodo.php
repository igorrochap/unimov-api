<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'municipio_id',
        'descricao',
        'inicio_inscricao',
        'fim_inscricao',
        'inicio',
        'fim',
    ];
}
