<?php

namespace App\Http\Controllers\Municipio;

use App\Actions\Municipio\CriaMunicipio;
use App\DTO\Request\Municipio\NovoMunicipioDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Municipio\NovoMunicipioRequest;
use Illuminate\Http\JsonResponse;

class MunicipioController extends Controller
{
    public function store(NovoMunicipioRequest $request, CriaMunicipio $action): JsonResponse
    {
        $municipio = $action->executa(NovoMunicipioDTO::porRequest($request));
        return $this->criado($municipio);
    }
}
