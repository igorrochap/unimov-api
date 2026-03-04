<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

abstract class Controller
{
    public function sucesso(mixed $data = null): JsonResponse
    {
        return response()->json($data);
    }

    public function criado(mixed $registro): JsonResponse
    {
        return response()->json($registro, HttpResponse::HTTP_CREATED);
    }

    public function semConteudo(): JsonResponse
    {
        return response()->json([], HttpResponse::HTTP_NO_CONTENT);
    }
}
