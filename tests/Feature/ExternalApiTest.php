<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExternalApiTest extends TestCase
{
    public function test_rejeita_body_invalido_com_422(): void
    {
        $this->postJson('/api/external', [])
            ->assertStatus(422)
            ->assertJsonPath('status', 'error');
    }

    public function test_valida_gera_arquivo_e_retorna_200(): void
    {
        Storage::fake('public');

        $payload = [
            'nome'     => 'Ana',
            'email'    => 'ana@exemplo.com',
            'txt_data' => 'conteudo txt',
            'csv_data' => 'nome,email',
        ];

        $this->postJson('/api/external', $payload)
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['status', 'message', 'data' => ['file', 'url', 'size']]);

        // gerou exatamente 1 arquivo
        $this->assertCount(1, Storage::disk('public')->allFiles('gerados'));
    }

    public function test_segundo_envio_identico_usa_cache_e_nao_regera(): void
    {
        Storage::fake('public');

        $payload = [
            'nome'     => 'Ana',
            'email'    => 'ana@exemplo.com',
            'txt_data' => 'conteudo txt',
            'csv_data' => 'nome,email',
        ];

        $this->postJson('/api/external', $payload)->assertOk(); // MISS -> gera
        $this->postJson('/api/external', $payload)->assertOk(); // HIT  -> não gera

        // continua com apenas 1 arquivo (o segundo veio do cache)
        $this->assertCount(1, Storage::disk('public')->allFiles('gerados'));
    }
}
