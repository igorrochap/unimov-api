<?php

namespace App\Actions\Usuario;

use App\DTO\Response\Usuario\DadosUsuario;
use App\Models\Usuario;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ListaUsuarios
{
    public function executa(): LengthAwarePaginator
    {
        return Usuario::query()
            ->with('municipio')
            ->paginate()
            ->through(fn (Usuario $usuario) => DadosUsuario::porUsuario($usuario));
    }
}
