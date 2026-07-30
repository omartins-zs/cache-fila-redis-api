# Como Executar — Cache & Fila com Redis (API)

Escolha **um** guia conforme seu ambiente:

| Guia | Quando usar | Requisitos no PC |
| --- | --- | --- |
| **[COMO_EXECUTAR_DOCKER.md](COMO_EXECUTAR_DOCKER.md)** | Executar em qualquer máquina com containers | Docker Desktop |
| **[COMO_EXECUTAR_LOCAL.md](COMO_EXECUTAR_LOCAL.md)** | Desenvolver com Laragon, XAMPP ou Artisan | PHP, Composer e Redis |

Este é o **Projeto 3 (API)** de uma cadeia de 3 microserviços. Recebe o body enviado pelo Back-end, valida, gera um arquivo e usa **cache no Redis**.

---

## Início rápido

### Local — Laragon ou XAMPP

Ative o bloco `LOCAL` no `.env` e execute:

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve --port=8002
```

Aplicação: http://127.0.0.1:8002 · Logs: http://127.0.0.1:8002/log-viewer

### Docker

Ative o bloco `DOCKER` no `.env` e execute:

```bash
cp .env.example .env
docker compose up -d --build
```

Aplicação: http://localhost:8082 · Logs: http://localhost:8082/log-viewer

---

## Logins demo

Este projeto **não possui tela de login**. O endpoint `POST /api/external` é público (exercício de estudo local).

---

## URLs principais

| Área | Local | Docker |
| --- | --- | --- |
| Endpoint da API (POST) | http://127.0.0.1:8002/api/external | http://localhost:8082/api/external |
| Log Viewer | http://127.0.0.1:8002/log-viewer | http://localhost:8082/log-viewer |

---

## Outros documentos

- [README.md](../README.md) — Visão geral da API e do fluxo dos 3 projetos

- [ACESSOS_TESTES.md](ACESSOS_TESTES.md) — Credenciais, URLs e como testar o fluxo
