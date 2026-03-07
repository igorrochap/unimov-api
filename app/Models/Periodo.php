<?php

namespace App\Models;

use App\Models\Municipio\Municipio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Periodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'municipio_id',
        'descricao',
        'inicio_inscricao',
        'fim_inscricao',
        'inicio',
        'fim',
    ];

    // converte as Strings em tipo Carbon
    protected $casts = [
        'inicio_inscricao' => 'datetime',
        'fim_inscricao' => 'datetime',
        'inicio' => 'datetime',
        'fim' => 'datetime',
    ];

    /**
     * Relacionamento com o Município
     */
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }
}
