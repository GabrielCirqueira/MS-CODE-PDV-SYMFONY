<?php

namespace App\Service;

use App\Exception\CpfInvalidoException;
use Psr\Log\LoggerInterface;

class ValidarCpfService
{

    public function execute(string $cpf): bool
    {
        try {
            $cpf = preg_replace('/[^0-9]/', '', $cpf);

            if (strlen($cpf) !== 11) {
                throw new CpfInvalidoException("CPF com comprimento incorreto: $cpf");
            }

            if (preg_match('/(\d)\1{10}/', $cpf)) {
                throw new CpfInvalidoException("CPF com todos os dígitos iguais: $cpf");
            }

            return true;     
        } catch (CpfInvalidoException $e) {

            return false;
        }
    }
}