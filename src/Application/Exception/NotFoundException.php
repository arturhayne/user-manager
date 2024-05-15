<?php

namespace UserManager\Application\Exception;

class NotFoundException extends \Exception
{
    protected $code = 404;
}
