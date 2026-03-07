<?php

namespace App\Actions\Municipio;

use App\DTO\Request\Municipio\NovoMunicipioDTO;
use App\DTO\Response\Municipio\NovoMunicipio;

final readonly class CriaMunicipio {
    public function executa(NovoMunicipioDTO $dto): NovoMunicipio
    {
        $municipio = Municipio::query()->create($dto->toArray());
        return NovoMunicipio::porMunicipio($municipio);

    }
}
