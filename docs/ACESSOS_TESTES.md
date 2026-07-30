# 🔐 Acessos e Dados de Teste

> **Importante:** este projeto (API) **não possui tela de login nem painel administrativo**. É um microserviço que recebe um body, valida, gera um arquivo e usa cache no Redis. O endpoint é **público** (exercício de estudo local).

## 1. Acesso ao Sistema (Usuários de Teste)

O `DatabaseSeeder` cria um usuário padrão (herdado do starter kit do Laravel), mas ele **não é usado por nenhuma rota** — não há autenticação neste projeto. Existe apenas a rota `GET /user` protegida por Sanctum, sem emissão de token no código.

| Perfil | E-mail / Usuário | Senha | Permissão / Detalhes |
| --- | --- | --- | --- |
| Usuário do seeder (não utilizado) | `test@example.com` | `password` | Criado via `User::factory()`; não há login para utilizá-lo |

## 2. URLs Principais

Não há rota `/login`. As URLs reais são o endpoint da API e o Log Viewer.

| Ambiente | Endpoint (POST) | Log Viewer |
| --- | --- | --- |
| **Docker** | `http://localhost:8082/api/external` | `http://localhost:8082/log-viewer` |
| **Local** (`php artisan serve`) | `http://127.0.0.1:8002/api/external` | `http://127.0.0.1:8002/log-viewer` |

## 3. Vitrine Pública / Páginas para Clientes

Este projeto é um microserviço de API (sem vitrine pública). Endpoints disponíveis:

| Item | Link (Exemplo Docker) |
| --- | --- |
| Gerar arquivo (POST, JSON) | `http://localhost:8082/api/external` |
| Log Viewer (visualização dos logs) | `http://localhost:8082/log-viewer` |

Exemplo de teste via `curl`:

```bash
curl -X POST http://127.0.0.1:8002/api/external \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"nome":"Ana","email":"ana@teste.com","txt_data":"abc","csv_data":"x,y"}'
```

## 4. Validação do Acesso

| Verificação | Resultado Esperado |
| --- | --- |
| Containers (`app`, `nginx`, `redis`) | Saudáveis / Rodando |
| `POST /api/external` com body válido | HTTP `200` com `status: success` |
| Segundo envio idêntico | Log `🔁 Cache HIT (Redis)` — arquivo não é regerado |

## 5. Carregar Dados de Teste

**Com Docker:**

```bash
docker compose exec app php artisan migrate:fresh --seed
```

**Rodando Localmente (Sem Docker):**

```bash
php artisan migrate:fresh --seed
```

---

### 📝 Observações:

- A geração do arquivo é cacheada no **Redis**: bodies idênticos retornam do cache sem regerar.
- Use estas informações **apenas** em ambiente local ou Docker de desenvolvimento.
