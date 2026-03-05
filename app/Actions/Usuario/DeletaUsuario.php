<?php

namespace App\Actions\Usuario;

use App\Models\Usuario;

final readonly class DeletaUsuario
{
    public function executa(string $uuid): void
    {
        Usuario::query()
            ->where('uuid', $uuid)
            ->firstOrFail()
            ->delete();
    }
}
