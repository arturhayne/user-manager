<?php

namespace UserManager\Application\Exception;

class UserNameAlreadyExistsException extends \Exception
{
    protected $code = 400;

    public function __construct($username)
    {
        parent::__construct(sprintf('Username %s already exists.', $username), $this->code);
    }
}
