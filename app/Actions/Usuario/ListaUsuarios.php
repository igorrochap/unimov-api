<?php

namespace App\Actions\Usuario;

use App\DTO\Response\Usuario\UsuarioListado;
use App\Models\Usuario;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ListaUsuarios
{
    public function executa(): LengthAwarePaginator
    {
        return Usuario::query()
            ->with('municipio')
            ->paginate()
            ->through(fn (Usuario $usuario) => UsuarioListado::porUsuario($usuario));
    }
}
