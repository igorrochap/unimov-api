<?php

namespace App\Models;

use App\Enums\Perfil;
use App\Models\Municipio\Municipio;
use App\Support\ValueObjects\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UsuarioFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'nome',
        'cpf',
        'email',
        'senha',
        'municipio_id',
        'perfil',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'senha',
        'remember_token',
    ];

    public function getAuthPassword(): string
    {
        return $this->senha;
    }

    /** @return BelongsTo<Municipio, $this> */
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(static function (Usuario $usuario) {
            $usuario->uuid = UUID::cria()->recupera();
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'senha' => 'hashed',
            'perfil' => Perfil::class,
        ];
    }
}
