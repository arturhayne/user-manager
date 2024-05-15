<?php

namespace UserManager\Domain\Exception;

class InvalidFieldsException extends \Exception
{
    protected $code = 400;

    public function __construct(array $errors)
    {
        $errorMessage = json_encode($errors);

        parent::__construct($errorMessage, $this->code);
    }
}
