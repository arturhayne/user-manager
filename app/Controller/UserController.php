<?php

declare(strict_types=1);

namespace App\Controller;

use UserManager\Application\Exception\NotFoundException;
use UserManager\Application\Exception\UserNameAlreadyExistsException;
use UserManager\Application\UserCommand;
use UserManager\Application\UserCommandHandler;
use UserManager\Domain\Exception\InvalidFieldsException;

class UserController extends Controller
{
    public function __construct(private UserCommandHandler $handler)
    {
    }

    public function store(string $populationId, array $request)
    {
        try {
            return json_encode(['id' => $this->handler->execute(UserCommand::create($populationId, $request))]);
        } catch (UserNameAlreadyExistsException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        } catch (NotFoundException $e) {
            return $this->notFound();
        } catch (InvalidFieldsException $e) {
            return $this->error($e->getCode(), $e->getErrors());
        } catch (\Throwable $e) {
            return $this->internalServerError($e->getCode(), $e->getMessage());
        }
    }
}
