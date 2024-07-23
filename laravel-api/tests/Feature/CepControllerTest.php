<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CepControllerTest extends TestCase
{
    public function test_valid_ceps()
    {
        // Simular a resposta da API do ViaCEP para os CEPs válidos
        Http::fake([
            'viacep.com.br/ws/01001000/json/' => Http::response([
                'cep' => '01001000',
                'logradouro' => 'Praça da Sé',
                'complemento' => 'lado ímpar',
                'bairro' => 'Sé',
                'localidade' => 'São Paulo',
                'uf' => 'SP',
                'ibge' => '3550308',
                'gia' => '1004',
                'ddd' => '11',
                'siafi' => '7107'
            ], 200),
            'viacep.com.br/ws/17560246/json/' => Http::response([
                'cep' => '17560246',
                'logradouro' => 'Avenida Paulista',
                'complemento' => 'de 1600/1601 a 1698/1699',
                'bairro' => 'CECAP',
                'localidade' => 'Vera Cruz',
                'uf' => 'SP',
                'ibge' => '3556602',
                'gia' => '7134',
                'ddd' => '14',
                'siafi' => '7235'
            ], 200)
        ]);

        $response = $this->get('/search/local/01001000,17560246');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'cep',
                'label',
                'logradouro',
                'complemento',
                'bairro',
                'localidade',
                'uf',
                'ibge',
                'gia',
                'ddd',
                'siafi'
            ]
        ]);
    }

    public function test_invalid_cep()
    {
         // Simular a resposta da API do ViaCEP para um CEP inválido
         Http::fake([
            'viacep.com.br/ws/00000000/json/' => Http::response(['erro' => true], 200)
        ]);

        $response = $this->get('/search/local/00000000');

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid CEP']);
    }
}
