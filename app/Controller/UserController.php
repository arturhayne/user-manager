<?php

declare(strict_types=1);

namespace App\Controller;

use UserManager\Application\Exception\UserNameAlreadyExistsException;
use UserManager\Application\UserCommand;
use UserManager\Application\UserCommandHandler;

class UserController extends Controller
{
    public function __construct(private UserCommandHandler $handler)
    {
    }

    public function store(string $populationId, array $request)
    {
        try {
            return json_encode(['id' => $this->handler->execute(UserCommand::create($populationId, $request))]);
        } catch (\UserManager\Application\Exception\NotFoundException|UserNameAlreadyExistsException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        } catch (\UserManager\Domain\Exception\InvalidFieldsException $e) {
            return $this->error($e->getCode(), $e->getErrors());
        } catch (\Throwable $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }
}
