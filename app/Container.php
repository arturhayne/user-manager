<?php

declare(strict_types=1);

namespace App;

use App\Controller\UserValuesController;
use UserManager\Application\UserValuesCommandHandler;
use UserManager\Domain\UserValuesValidationService;
use UserManager\Domain\Validator;
use UserManager\Infrastructure\PdoUserRepository;
use UserManager\Infrastructure\SqliteUserValueRepository;
use UserManager\Infrastructure\UserRepository;

class Container
{
    private array $bindings = [];

    public function __construct()
    {
        $this->set(\SQLite3::class, function () {
            return new \SQLite3('database.sqlite');
        });

        $this->set(\PDO::class, function ($container) {
            return new \PDO('sqlite:database.sqlite');
        });

        $this->set(UserRepository::class, function ($container) {
            return new PdoUserRepository($container->get(\PDO::class));
        });

        $this->set(UserValuesController::class, function ($container) {
            return new UserValuesController($container->get(UserValuesCommandHandler::class));
        });

        $this->set(SqliteUserValueRepository::class, function ($container) {
            return new SqliteUserValueRepository($container->get(\SQLite3::class));
        });

        $this->set(Validator::class, function ($container) {
            return new Validator($container->get(SqliteUserValueRepository::class));
        });

        $this->set(UserValuesValidationService::class, function ($container) {
            return new UserValuesValidationService($container->get(Validator::class));
        });

        $this->set(UserValuesCommandHandler::class, function ($container) {
            return new UserValuesCommandHandler(
                $container->get(UserRepository::class),
                $container->get(UserValuesValidationService::class)
            );
        });
    }

    public function set(string $id, callable $factory): void
    {
        $this->bindings[$id] = $factory;
    }

    public function get(string $id)
    {
        if (!isset($this->bindings[$id])) {
            throw new \Exception("Target binding [$id] does not exist.");
        }

        $factory = $this->bindings[$id];

        return $factory($this);
    }
}
