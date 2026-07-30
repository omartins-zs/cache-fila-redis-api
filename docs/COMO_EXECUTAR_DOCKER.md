# Como Executar com Docker — Cache & Fila com Redis (API)

Guia para executar o sistema utilizando Docker Desktop.

---

## Stack e containers

| Container | Função | Porta |
| --- | --- | --- |
| nginx | Servidor web | 8082 |
| app | Laravel com PHP-FPM | Interna |
| redis | Cache | Interna |

Este projeto usa **SQLite** (arquivo), então não há container de banco nem PHPMyAdmin. Não usa fila, então não há worker.

---

## 1) Preparar ambiente

```bash
cp .env.example .env
```

Deixe o bloco `DOCKER` ativo e o bloco `LOCAL` comentado:

```env
# LOCAL
# APP_URL=http://127.0.0.1:8002
# REDIS_HOST=127.0.0.1

# DOCKER
APP_URL=http://localhost:8082
REDIS_HOST=redis
```

> Dentro do Docker, o Redis é acessado pelo nome do serviço `redis`.

---

## 2) Subir containers

```bash
docker compose up -d --build
docker compose ps
```

---

## 3) Inicialização e migrations

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

---

## 4) Desenvolvimento e cache

```bash
docker compose exec app php artisan optimize:clear
```

---

## 5) Acessos

| Recurso | URL |
| --- | --- |
| Endpoint da API (POST) | http://localhost:8082/api/external |
| Log Viewer | http://localhost:8082/log-viewer |

### Credenciais de teste

Este projeto **não possui tela de login** — o endpoint é público.

---

## 6) Logs e diagnóstico

```bash
docker compose logs -f
docker compose logs -f app
docker compose exec app php artisan about
```

---

## 7) Parar ou reconstruir

```bash
docker compose down
docker compose up -d --build
```

Para apagar também os volumes (dados do Redis):

```bash
docker compose down -v
```

> O comando `docker compose down -v` apaga o volume do Redis.
