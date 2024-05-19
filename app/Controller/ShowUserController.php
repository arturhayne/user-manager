<?php

declare(strict_types=1);

namespace App\Controller;

use UserManager\Application\PopulationQueryHandler;

class ShowUserController extends Controller
{
    public function __construct(private UserQueryHandler $handler)
    {
    }

    public function index()
    {
        try {
            return json_encode($this->handler->execute());
        } catch (\Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }
}
