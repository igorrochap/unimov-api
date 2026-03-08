<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

final readonly class DeslogaUsuario
{
    public function executa(): void
    {
        Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
