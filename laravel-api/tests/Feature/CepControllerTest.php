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
            'results' => [
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
            ]
        ]);
    }

    public function test_invalid_cep()
    {
        Http::fake([
            'viacep.com.br/ws/00000000/json/' => Http::response(['erro' => true], 200)
        ]);

        $response = $this->get('/search/local/00000000');

        $response->assertStatus(400);
        $response->assertJson([
            'errors' => [
                ['cep' => '00000000', 'error' => 'CEP inválido']
            ]
        ]);
    }

    public function test_empty_cep()
    {
        $response = $this->get('/search/local');

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Você precisa incluir um CEP válido']);
    }

    public function test_cep_with_letters()
    {
        $response = $this->get('/search/local/ABC12345');

        $response->assertStatus(400);
        $response->assertJson([
            'errors' => [
                ['cep' => 'ABC12345', 'error' => 'CEP com formato inválido, favor incluir certo']
            ]
        ]);
    }

    public function test_multiple_ceps_with_one_invalid()
    {
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
            'viacep.com.br/ws/00000000/json/' => Http::response(['erro' => true], 200)
        ]);

        $response = $this->get('/search/local/01001000,00000000');

        $response->assertStatus(400);
        $response->assertJson([
            'errors' => [
                ['cep' => '00000000', 'error' => 'CEP inválido']
            ]
        ]);
    }
}
?>
