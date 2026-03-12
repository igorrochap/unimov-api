<?php

namespace App\DTO\Request\Auth;

use App\Http\Requests\Auth\LoginRequest;

final readonly class LoginDTO
{
    public function __construct(
        public string $credencial,
        public string $senha,
    ) {}

    public static function porRequest(LoginRequest $request): LoginDTO
    {
        return new self(
            $request->string('credencial'),
            $request->string('senha'),
        );
    }
}
