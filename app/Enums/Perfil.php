<?php

namespace App\Enums;

enum Perfil: string
{
    case Admin = 'admin';
    case Secretaria = 'secretaria';
    case Fiscal = 'fiscal';
    case Motorista = 'motorista';
    case Aluno = 'aluno';
}
