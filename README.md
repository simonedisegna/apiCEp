# :incoming_envelope: API de Consulta de CEP :incoming_envelope:

## :pushpin: Visão Geral

Esta API permite consultar informações de múltiplos CEPs utilizando o serviço ViaCEP. Retorna os dados dos endereços em formato JSON.

## :pushpin: Requisitos

- PHP 7.4 ou superior
- Composer
- XAMPP ou qualquer servidor web local
- Laravel 8 ou superior

## :pushpin: Configuração

### Clonar o Repositório

```bash
git clone https://github.com/simonedisegna/apiCEp.git
cd laravel-api
```
### Instalar Dependências
```bash
composer install
```
### Configurar o Arquivo .env
Copie o arquivo de exemplo .env.example para .env e configure as variáveis de ambiente conforme necessário.
```
cp .env.example .env
```
### Gerar a Chave da Aplicação
```
php artisan key:generate
```
### Executar a Aplicação
```
php artisan serve
```

# Testes com PHPUnit
A API inclui testes unitários utilizando PHPUnit para garantir que todas as funcionalidades estejam funcionando corretamente.

## Executar os Testes
Para executar os testes, utilize o seguinte comando:
```
php artisan test
```
<img src="https://github.com/simonedisegna/apiCEp/blob/main/img/Teste_phpUnit.jpg" alt="Disegna" width="400">
Os seguintes cenários são cobertos pelos testes:

**Consulta de CEPs válidos:** Verifica se a API retorna corretamente as informações dos CEPs válidos.
**Consulta de CEP inválido:** Verifica se a API retorna um erro apropriado para um CEP inválido.
**Consulta sem fornecer CEP:** Verifica se a API retorna um erro apropriado quando nenhum CEP é fornecido.
**Consulta de CEP com letras:** Verifica se a API retorna um erro apropriado para um CEP com letras.
**Consulta de múltiplos CEPs com um inválido:** Verifica se a API lida corretamente com múltiplos CEPs quando um deles é inválido.

# Endpoints
## Consultar Informações de CEP

Rota: GET /search/local/{ceps?}

Descrição: Consulta informações de um ou mais CEPs.

Parâmetros:

{ceps}: String opcional contendo um ou mais CEPs separados por vírgula.
Respostas:

200 OK: Quando os CEPs são válidos.
```
{
  "results": [
    {
      "cep": "01001000",
      "label": "Praça da Sé, São Paulo",
      "logradouro": "Praça da Sé",
      "complemento": "lado ímpar",
      "bairro": "Sé",
      "localidade": "São Paulo",
      "uf": "SP",
      "ibge": "3550308",
      "gia": "1004",
      "ddd": "11",
      "siafi": "7107"
    }
  ]
}
```
400 Bad Request: Quando há erros na consulta.
```
{
  "errors": [
    {
      "cep": "00000000",
      "error": "CEP inválido"
    },
    {
      "cep": "ABC12345",
      "error": "CEP com formato inválido, favor incluir certo"
    }
  ]
}
```
400 Bad Request: Quando nenhum CEP é fornecido.
```
{
  "error": "Você precisa incluir um CEP válido"
}
```
# Exemplo de Uso
## Consultar Múltiplos CEPs Válidos
### Requisição:
```
GET /search/local/01001000,17560246
```
### Resposta:
```
{
  "results": [
    {
      "cep": "01001000",
      "label": "Praça da Sé, São Paulo",
      "logradouro": "Praça da Sé",
      "complemento": "lado ímpar",
      "bairro": "Sé",
      "localidade": "São Paulo",
      "uf": "SP",
      "ibge": "3550308",
      "gia": "1004",
      "ddd": "11",
      "siafi": "7107"
    },
    {
      "cep": "17560246",
      "label": "Avenida Paulista, Vera Cruz",
      "logradouro": "Avenida Paulista",
      "complemento": "de 1600/1601 a 1698/1699",
      "bairro": "CECAP",
      "localidade": "Vera Cruz",
      "uf": "SP",
      "ibge": "3556602",
      "gia": "7134",
      "ddd": "14",
      "siafi": "7235"
    }
  ]
}
```
## Consultar CEP Inválido
### Requisição:
```
GET /search/local/00000000
```
### Resposta:
```
{
  "errors": [
    {
      "cep": "00000000",
      "error": "CEP inválido"
    }
  ]
}
```
## Consultar CEP com Letras
### Requisição:
```
GET /search/local/ABC12345
```
### Resposta:
```
{
  "errors": [
    {
      "cep": "ABC12345",
      "error": "CEP com formato inválido, favor incluir certo"
    }
  ]
}
```
## Consultar sem Fornecer CEP
### Requisição:
```
GET /search/local/
```
### Resposta:
```
{
  "error": "Você precisa incluir um CEP válido"
}
```
## Tela como deve se comportar
http://localhost:8000/search/local/17560246,01001000
<img src="https://github.com/simonedisegna/apiCEp/blob/main/img/apiCep.jpg" alt="Disegna" width="600">

# Licença
Este projeto está licenciado sob a MIT License.