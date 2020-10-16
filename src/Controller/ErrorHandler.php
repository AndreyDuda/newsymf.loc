<?php

declare(strict_types=1);

namespace App\Controller;

class ErrorHandler
{
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    public function handle(\DomainException $e): void
    {
        $this->errors->handle($e);
    }
}