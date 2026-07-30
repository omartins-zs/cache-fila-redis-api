# 🌐 Cache & Fila com Redis — Projeto 3 (API · Laravel + Redis Cache)

Projeto de **estudo** de **Cache com Redis** e **Fila (Queue) com Redis** dividido em 3 microserviços. Esta é a **API**, onde acontece o **Cache com Redis**.

> Fluxo geral: [Front-end](https://github.com/omartins-zs/cache-fila-redis-vue-front-end) → [Back-end (fila/Redis)](https://github.com/omartins-zs/cache-fila-redis-back-end) → **API (esta)**

## Responsabilidade
Recebe o body enviado pelo Projeto 2, **valida**, **gera um arquivo** a partir dos dados e retorna `JSON 200`.

Contém: **1 Route API** e **1 Controller**.

| Peça | Arquivo |
|------|---------|
| Route API | `routes/api.php` → `POST /api/external` |
| Controller | `app/Http/Controllers/ExternalApiController.php` |

## Cache com Redis
O resultado da geração é cacheado no Redis por um **hash do body**:
```php
Cache::store('redis')->remember($cacheKey, now()->addMinutes(10), fn () => $this->generateFile($validated));
```
- Body novo → **Cache MISS** (gera o arquivo).
- Mesmo body de novo → **Cache HIT** (retorna do Redis sem regerar).

Configurado com `CACHE_STORE=redis` no `.env`.

## Padrão de resposta
```json
{ "status": "success", "message": "Arquivo validado e gerado com sucesso.", "data": { "file": "...", "url": "...", "size": 123 } }
```

## Log Viewer
Os logs (com emojis) podem ser vistos no navegador via [opcodesio/log-viewer](https://github.com/opcodesio/log-viewer):

👉 **http://localhost:8002/log-viewer**

## Como rodar
```bash
composer install
php artisan serve --port=8002
```
Precisa de um Redis rodando (ex.: `docker run -d -p 6379:6379 redis:latest`).
