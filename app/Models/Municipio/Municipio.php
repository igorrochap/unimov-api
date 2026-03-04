<?php

namespace App\Models\Municipio;

use App\Support\ValueObjects\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'nome',
        'uf',
        'secretaria_responsavel',
        'email',
        'telefone',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Municipio $municipio) {
            $municipio->uuid = UUID::cria()->recupera();
        });
    }
}
