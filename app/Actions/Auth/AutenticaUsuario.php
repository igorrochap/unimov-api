<?php

namespace App\Actions\Auth;

use App\DTO\Request\Auth\LoginDTO;
use App\DTO\Response\Auth\UsuarioAutenticado;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final readonly class AutenticaUsuario
{
    /**
     * @throws ValidationException
     */
    public function executa(LoginDTO $dto): UsuarioAutenticado
    {
        $campo = str_contains($dto->credencial, '@') ? 'email' : 'cpf';

        $autenticado = Auth::guard('web')->attempt([
            $campo => $dto->credencial,
            'password' => $dto->senha,
        ]);

        if (! $autenticado) {
            throw ValidationException::withMessages([
                'credencial' => 'As credenciais informadas estão incorretas.',
            ]);
        }

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        return UsuarioAutenticado::porUsuario($usuario);
    }
}
