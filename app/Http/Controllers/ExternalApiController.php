<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ExternalApiController extends Controller
{
    /**
     * Projeto 3 - Recebe o body do Projeto 2, valida, gera um arquivo a
     * partir dos dados e retorna JSON 200.
     *
     * CACHE COM REDIS: o resultado é cacheado por um hash do body. Se o
     * mesmo body chegar de novo dentro do TTL, devolvemos o resultado do
     * cache (Redis) sem gerar o arquivo outra vez.
     */
    public function processData(Request $request): JsonResponse
    {
        Log::info('🟢 ExternalApiController@processData recebido', $request->all());

        try {
            $validated = $request->validate([
                'nome'     => 'required|string',
                'email'    => 'required|email',
                'txt_data' => 'required|string',
                'csv_data' => 'required|string',
            ]);

            Log::info('✅ Todos os arquivos foram validados com sucesso.');

            // Chave de cache baseada no conteúdo do body.
            $cacheKey = 'arquivo_gerado:' . md5(json_encode($validated));

            // Usa o store padrão de cache (CACHE_STORE=redis no .env -> Redis).
            if (Cache::has($cacheKey)) {
                Log::info('🔁 Cache HIT (Redis) - retornando resultado já gerado', ['key' => $cacheKey]);
            } else {
                Log::info('🟡 Cache MISS (Redis) - gerando novo arquivo', ['key' => $cacheKey]);
            }

            // remember: se existir no cache retorna dele; senão executa a
            // closure (gera o arquivo) e guarda por 10 minutos.
            $result = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($validated) {
                return $this->generateFile($validated);
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'Arquivo validado e gerado com sucesso.',
                'data'    => $result,
            ], 200);
        } catch (ValidationException $e) {
            Log::error('🔴 Erro de validação do body', ['erros' => $e->errors()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Dados inválidos.',
                'data'    => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('❌ ExternalApiController@processData erro', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Erro ao processar: ' . $e->getMessage(),
                'data'    => null,
            ], 500);
        }
    }

    /**
     * Gera um arquivo a partir dos dados recebidos e devolve seus metadados.
     */
    private function generateFile(array $data): array
    {
        $fileName = 'gerado_' . now()->format('Ymd_His') . '_' . Str::random(8) . '.txt';

        $content = "Nome: {$data['nome']}\n"
            . "Email: {$data['email']}\n\n"
            . "=== TXT ===\n{$data['txt_data']}\n\n"
            . "=== CSV ===\n{$data['csv_data']}\n";

        Storage::disk('public')->put("gerados/{$fileName}", $content);

        Log::info("📄 Arquivo gerado: {$fileName}");

        return [
            'file' => $fileName,
            'url'  => Storage::disk('public')->url("gerados/{$fileName}"),
            'size' => strlen($content),
        ];
    }
}
