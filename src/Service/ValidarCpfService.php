<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ValidarCpfService
{
    private HttpClientInterface $httpClient;
    private string $token = "16195|HMlqJjlw5Eq2YGYoAbNpFPBQVURxTXKD";

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function execute(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        $response = $this->httpClient->request('GET', 'https://api.invertexto.com/v1/validator', [
            'query' => [
                'value' => $cpf,
                'type' => 'cpf',
                'token' => $this->token,
            ],
        ]);

        $data = $response->toArray();

        if (isset($data['valid']) && $data['valid']) {
            return true;
        } else {
            return false;
        }
    }
}
