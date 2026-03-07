<?php

namespace App\Actions\Periodo;

use App\Models\Periodo;

class DeletaPeriodo
{
    public function executa(int $id): void
    {
        Periodo::query()
            ->where('id', $id)
            ->firstOrFail()
            ->delete();
    }
}
