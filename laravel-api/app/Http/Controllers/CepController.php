<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    public function search($ceps = null)
    {
        if (is_null($ceps)) {
            return response()->json(['error' => 'Você precisa incluir um CEP válido'], 400);
        }

        $cepArray = explode(',', $ceps);
        $results = [];
        $errors = [];

        foreach ($cepArray as $cep) {
            if (!preg_match('/^[0-9]{8}$/', $cep)) {
                $errors[] = ['cep' => $cep, 'error' => 'CEP com formato inválido, favor incluir certo'];
                continue;
            }

            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
            $data = $response->json();

            if (isset($data['erro']) && $data['erro'] == true) {
                $errors[] = ['cep' => $cep, 'error' => "CEP inválido"];
                continue;
            }

            $results[] = [
                'cep' => $data['cep'],
                'label' => "{$data['logradouro']}, {$data['localidade']}",
                'logradouro' => $data['logradouro'],
                'complemento' => $data['complemento'],
                'bairro' => $data['bairro'],
                'localidade' => $data['localidade'],
                'uf' => $data['uf'],
                'ibge' => $data['ibge'],
                'gia' => $data['gia'],
                'ddd' => $data['ddd'],
                'siafi' => $data['siafi']
            ];
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 400);
        }

        return response()->json(['results' => $results]);
    }
}
?>
