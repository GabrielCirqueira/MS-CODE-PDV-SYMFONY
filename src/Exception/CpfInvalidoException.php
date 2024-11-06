<?php

namespace App\Exception;

use Exception;

class CpfInvalidoException extends Exception
{
    protected $message = 'O CPF fornecido é inválido.';
}
