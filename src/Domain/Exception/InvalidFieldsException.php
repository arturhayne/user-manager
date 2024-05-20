<?php

namespace UserManager\Domain\Exception;

class InvalidFieldsException extends \Exception
{
    protected $code = 400;

    private array $errors;

    public function __construct(array $errors)
    {
        parent::__construct(json_encode($errors), $this->code);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
