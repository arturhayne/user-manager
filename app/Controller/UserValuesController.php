<?php

declare(strict_types=1);

namespace App\Controller;

use UserManager\Application\Exception\NotFoundException;
use UserManager\Application\UserValuesCommand;
use UserManager\Application\UserValuesCommandHandler;
use UserManager\Domain\Exception\InvalidFieldsException;

class UserValuesController extends Controller
{
    public function __construct(private UserValuesCommandHandler $handler)
    {
    }

    public function validate(int $userId, array $request)
    {
        try {
            $this->handler->execute(UserValuesCommand::createFromArray($userId, $request));
        } catch (InvalidFieldsException $e) {
            return $this->error((int) $e->getCode(), $e->getMessage());
        } catch (NotFoundException) {
            return $this->notFound();
        } catch (\Throwable $e) {
            return $this->internalServerError();
        }
    }
}
