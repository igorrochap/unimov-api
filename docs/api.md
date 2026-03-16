# Documentação da API — Unimov

Base URL: `/api`

Autenticação: SPA stateful via **Laravel Sanctum** (cookie de sessão + CSRF). Consulte [`docs/autenticacao.md`](./autenticacao.md) para detalhes de integração.

---

## Índice

- [Autenticação](#autenticação)
  - [Login](#login)
  - [Logout](#logout)
  - [Usuário autenticado](#usuário-autenticado)
- [Usuários](#usuários)
  - [Listar usuários](#listar-usuários)
  - [Criar usuário](#criar-usuário)
  - [Atualizar usuário](#atualizar-usuário)
  - [Excluir usuário](#excluir-usuário)
- [Períodos](#períodos)
  - [Listar períodos](#listar-períodos)
  - [Criar período](#criar-período)
  - [Atualizar período](#atualizar-período)
  - [Excluir período](#excluir-período)
- [Perfis de acesso](#perfis-de-acesso)

---

## Autenticação

### Login

`POST /api/auth/login`

**Autenticação:** Não requerida.

Autentica um usuário a partir de e-mail ou CPF + senha. Em caso de sucesso, inicia uma sessão e retorna os dados do usuário autenticado.

#### Corpo da requisição

| Campo       | Tipo   | Obrigatório | Descrição                                |
|-------------|--------|-------------|------------------------------------------|
| `credencial`| string | Sim         | E-mail ou CPF do usuário                 |
| `senha`     | string | Sim         | Senha do usuário                         |

#### Resposta de sucesso — `200 OK`

```json
{
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "nome": "João da Silva",
  "email": "joao@example.com",
  "perfil": "motorista"
}
```

#### Respostas de erro

| Código | Cenário                                        |
|--------|------------------------------------------------|
| `422`  | Credencial ou senha não informadas             |
| `422`  | Credenciais inválidas (usuário não encontrado) |

---

### Logout

`POST /api/auth/logout`

**Autenticação:** Requer `auth:sanctum`.

Encerra a sessão do usuário autenticado, invalidando o token de sessão e regenerando o CSRF token.

#### Resposta de sucesso — `204 No Content`

Sem corpo na resposta.

#### Respostas de erro

| Código | Cenário               |
|--------|-----------------------|
| `401`  | Usuário não autenticado |

---

### Usuário autenticado

`GET /api/auth/me`

**Autenticação:** Requer `auth:sanctum`.

Retorna os dados do usuário da sessão atual.

#### Resposta de sucesso — `200 OK`

```json
{
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "nome": "João da Silva",
  "email": "joao@example.com",
  "perfil": "secretaria"
}
```

#### Respostas de erro

| Código | Cenário               |
|--------|-----------------------|
| `401`  | Usuário não autenticado |

---

## Usuários

Todos os endpoints abaixo requerem autenticação via `auth:sanctum` e perfil **`admin`**.

### Listar usuários

`GET /api/usuarios`

**Autenticação:** Requer `auth:sanctum` + perfil `admin`.

Retorna a lista paginada de todos os usuários cadastrados, com os dados do município de cada um.

#### Resposta de sucesso — `200 OK`

Resposta paginada padrão do Laravel:

```json
{
  "data": [
    {
      "uuid": "550e8400-e29b-41d4-a716-446655440000",
      "nome": "João da Silva",
      "email": "joao@example.com",
      "municipio": "Maceió",
      "perfil": "aluno"
    }
  ],
  "current_page": 1,
  "last_page": 3,
  "per_page": 15,
  "total": 42
}
```

#### Respostas de erro

| Código | Cenário                    |
|--------|----------------------------|
| `401`  | Usuário não autenticado    |
| `403`  | Perfil sem permissão       |

---

### Criar usuário

`POST /api/usuarios`

**Autenticação:** Requer `auth:sanctum` + perfil `admin`.

Cadastra um novo usuário no sistema.

#### Corpo da requisição

| Campo       | Tipo   | Obrigatório | Validações                                              | Descrição                                          |
|-------------|--------|-------------|---------------------------------------------------------|----------------------------------------------------|
| `nome`      | string | Sim         | —                                                       | Nome completo do usuário                           |
| `email`     | string | Sim         | Formato de e-mail válido; único em `usuarios`           | E-mail do usuário                                  |
| `cpf`       | string | Sim         | Único em `usuarios`                                     | CPF do usuário (somente dígitos recomendado)       |
| `senha`     | string | Sim         | —                                                       | Senha de acesso (armazenada com hash bcrypt)        |
| `municipio` | inteiro| Sim         | Deve referenciar um `id` existente em `municipios`      | ID do município do usuário                         |
| `perfil`    | string | Sim         | Um dos valores: `admin`, `secretaria`, `fiscal`, `motorista`, `aluno` | Perfil de acesso do usuário |

#### Resposta de sucesso — `201 Created`

```json
{
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "nome": "João da Silva",
  "email": "joao@example.com"
}
```

#### Respostas de erro

| Código | Cenário                                                  |
|--------|----------------------------------------------------------|
| `401`  | Usuário não autenticado                                  |
| `403`  | Perfil sem permissão                                     |
| `422`  | Campos obrigatórios ausentes ou inválidos                |
| `422`  | E-mail ou CPF já cadastrados                             |
| `422`  | `perfil` com valor fora dos permitidos                   |
| `422`  | `municipio` inexistente                                  |

**Formato de erro de validação (`422`):**

```json
{
  "message": "O e-mail informado é inválido.",
  "errors": {
    "email": ["O e-mail informado é inválido."]
  }
}
```

---

### Atualizar usuário

`PUT /api/usuarios/{uuid}`

**Autenticação:** Requer `auth:sanctum` + perfil `admin`.

Atualiza os dados de um usuário existente identificado pelo UUID.

#### Parâmetros de path

| Parâmetro | Tipo   | Descrição          |
|-----------|--------|--------------------|
| `uuid`    | string | UUID do usuário    |

#### Corpo da requisição

| Campo       | Tipo   | Obrigatório | Validações                                                          | Descrição                                          |
|-------------|--------|-------------|---------------------------------------------------------------------|----------------------------------------------------|
| `nome`      | string | Sim         | —                                                                   | Nome completo do usuário                           |
| `email`     | string | Sim         | Formato de e-mail válido; único em `usuarios` (ignora o próprio)    | E-mail do usuário                                  |
| `cpf`       | string | Sim         | Único em `usuarios` (ignora o próprio)                              | CPF do usuário                                     |
| `municipio` | inteiro| Sim         | —                                                                   | ID do município do usuário                         |
| `perfil`    | string | Sim         | Um dos valores: `admin`, `secretaria`, `fiscal`, `motorista`, `aluno` | Perfil de acesso do usuário                     |
| `senha`     | string | Não         | Ignorado se não informado                                           | Nova senha (armazenada com hash bcrypt)             |

#### Resposta de sucesso — `200 OK`

```json
{
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "nome": "João da Silva",
  "email": "joao@example.com",
  "municipio": "Maceió",
  "perfil": "fiscal"
}
```

#### Respostas de erro

| Código | Cenário                                                  |
|--------|----------------------------------------------------------|
| `401`  | Usuário não autenticado                                  |
| `403`  | Perfil sem permissão                                     |
| `404`  | UUID não encontrado                                      |
| `422`  | Campos obrigatórios ausentes ou inválidos                |
| `422`  | E-mail ou CPF já em uso por outro usuário                |

---

### Excluir usuário

`DELETE /api/usuarios/{uuid}`

**Autenticação:** Requer `auth:sanctum` + perfil `admin`.

Remove permanentemente um usuário do sistema.

#### Parâmetros de path

| Parâmetro | Tipo   | Descrição       |
|-----------|--------|-----------------|
| `uuid`    | string | UUID do usuário |

#### Resposta de sucesso — `204 No Content`

Sem corpo na resposta.

#### Respostas de erro

| Código | Cenário                 |
|--------|-------------------------|
| `401`  | Usuário não autenticado |
| `403`  | Perfil sem permissão    |
| `404`  | UUID não encontrado     |

---

## Períodos

Todos os endpoints abaixo requerem autenticação via `auth:sanctum` e perfil **`admin`** ou **`secretaria`**.

### Listar períodos

`GET /api/periodos`

**Autenticação:** Requer `auth:sanctum` + perfil `admin` ou `secretaria`.

Retorna a lista paginada de todos os períodos cadastrados, ordenados por data de início (mais recentes primeiro).

#### Resposta de sucesso — `200 OK`

Resposta paginada padrão do Laravel:

```json
{
  "data": [
    {
      "id": 1,
      "municipio_nome": "Maceió",
      "descricao": "Período 2024/1",
      "inicio_inscricao": "2024-01-01 00:00:00",
      "fim_inscricao": "2024-01-31 00:00:00",
      "inicio": "2024-02-01 00:00:00",
      "fim": "2024-12-31 00:00:00"
    }
  ],
  "current_page": 1,
  "last_page": 2,
  "per_page": 15,
  "total": 20
}
```

#### Respostas de erro

| Código | Cenário                 |
|--------|-------------------------|
| `401`  | Usuário não autenticado |
| `403`  | Perfil sem permissão    |

---

### Criar período

`POST /api/periodos`

**Autenticação:** Requer `auth:sanctum` + perfil `admin` ou `secretaria`.

Cadastra um novo período no sistema.

#### Corpo da requisição

| Campo               | Tipo   | Obrigatório | Validações                                          | Descrição                                              |
|---------------------|--------|-------------|-----------------------------------------------------|--------------------------------------------------------|
| `municipio_id`      | inteiro| Sim         | Deve referenciar um `id` existente em `municipios`  | ID do município do período                             |
| `descricao`         | string | Sim         | —                                                   | Descrição do período (ex.: "Período 2024/1")           |
| `inicio_inscricao`  | string | Sim         | Formato de data/hora válido                         | Data e hora de início das inscrições                   |
| `fim_inscricao`     | string | Sim         | Data/hora válida; deve ser posterior a `inicio_inscricao` | Data e hora de término das inscrições           |
| `inicio`            | string | Sim         | Formato de data/hora válido                         | Data e hora de início operacional do período           |
| `fim`               | string | Sim         | Data/hora válida; deve ser posterior a `inicio`     | Data e hora de término operacional do período          |

#### Resposta de sucesso — `201 Created`

```json
{
  "id": 1,
  "municipio_nome": "Maceió",
  "descricao": "Período 2024/1",
  "inicio_inscricao": "2024-01-01 00:00:00",
  "fim_inscricao": "2024-01-31 00:00:00",
  "inicio": "2024-02-01 00:00:00",
  "fim": "2024-12-31 00:00:00"
}
```

#### Respostas de erro

| Código | Cenário                                                  |
|--------|----------------------------------------------------------|
| `401`  | Usuário não autenticado                                  |
| `403`  | Perfil sem permissão                                     |
| `422`  | Campos obrigatórios ausentes ou inválidos                |
| `422`  | `municipio_id` inexistente                               |
| `422`  | `fim_inscricao` não é posterior a `inicio_inscricao`     |
| `422`  | `fim` não é posterior a `inicio`                        |

**Formato de erro de validação (`422`):**

```json
{
  "message": "O município é obrigatório.",
  "errors": {
    "municipio_id": ["O município é obrigatório."]
  }
}
```

---

### Atualizar período

`PUT /api/periodos/{id}`

**Autenticação:** Requer `auth:sanctum` + perfil `admin` ou `secretaria`.

Atualiza os dados de um período existente identificado pelo ID.

#### Parâmetros de path

| Parâmetro | Tipo    | Descrição        |
|-----------|---------|------------------|
| `id`      | inteiro | ID do período    |

#### Corpo da requisição

Mesmos campos e validações do endpoint de criação.

| Campo               | Tipo   | Obrigatório | Validações                                          | Descrição                                              |
|---------------------|--------|-------------|-----------------------------------------------------|--------------------------------------------------------|
| `municipio_id`      | inteiro| Sim         | Deve referenciar um `id` existente em `municipios`  | ID do município do período                             |
| `descricao`         | string | Sim         | —                                                   | Descrição do período                                   |
| `inicio_inscricao`  | string | Sim         | Formato de data/hora válido                         | Data e hora de início das inscrições                   |
| `fim_inscricao`     | string | Sim         | Data/hora válida; deve ser posterior a `inicio_inscricao` | Data e hora de término das inscrições           |
| `inicio`            | string | Sim         | Formato de data/hora válido                         | Data e hora de início operacional do período           |
| `fim`               | string | Sim         | Data/hora válida; deve ser posterior a `inicio`     | Data e hora de término operacional do período          |

#### Resposta de sucesso — `200 OK`

```json
{
  "id": 1,
  "municipio_nome": "Maceió",
  "descricao": "Período 2024/1 — Atualizado",
  "inicio_inscricao": "2024-01-05 00:00:00",
  "fim_inscricao": "2024-02-05 00:00:00",
  "inicio": "2024-03-01 00:00:00",
  "fim": "2024-12-31 00:00:00"
}
```

#### Respostas de erro

| Código | Cenário                                                  |
|--------|----------------------------------------------------------|
| `401`  | Usuário não autenticado                                  |
| `403`  | Perfil sem permissão                                     |
| `404`  | Período não encontrado                                   |
| `422`  | Campos obrigatórios ausentes ou inválidos                |
| `422`  | `municipio_id` inexistente                               |
| `422`  | `fim_inscricao` não é posterior a `inicio_inscricao`     |
| `422`  | `fim` não é posterior a `inicio`                        |

---

### Excluir período

`DELETE /api/periodos/{id}`

**Autenticação:** Requer `auth:sanctum` + perfil `admin` ou `secretaria`.

Remove permanentemente um período do sistema.

#### Parâmetros de path

| Parâmetro | Tipo    | Descrição        |
|-----------|---------|------------------|
| `id`      | inteiro | ID do período    |

#### Resposta de sucesso — `204 No Content`

Sem corpo na resposta.

#### Respostas de erro

| Código | Cenário                 |
|--------|-------------------------|
| `401`  | Usuário não autenticado |
| `403`  | Perfil sem permissão    |
| `404`  | Período não encontrado  |

---

## Perfis de acesso

O campo `perfil` determina o nível de acesso de um usuário no sistema:

| Valor        | Descrição                    |
|--------------|------------------------------|
| `admin`      | Administrador do sistema     |
| `secretaria` | Secretaria de educação       |
| `fiscal`     | Fiscal de transporte         |
| `motorista`  | Motorista                    |
| `aluno`      | Aluno                        |
