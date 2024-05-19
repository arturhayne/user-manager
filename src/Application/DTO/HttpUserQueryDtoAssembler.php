<?php

declare(strict_types=1);

namespace UserManager\Application\DTO;

use UserManager\Domain\User;

class HttpUserQueryDtoAssembler implements \JsonSerializable
{
    private $response;

    private function __construct(User $user)
    {
        $this->response = $user;
    }

    public static function create(User $user)
    {
        return new self($user);
    }

    public function jsonSerialize(): mixed
    {
        return $this->response;
    }
}
