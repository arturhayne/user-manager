<?php

declare(strict_types=1);

namespace Tests\System;

use App\Container;
use PHPUnit\Framework\TestCase;
use UserManager\Application\PopulationQueryHandler;

class PopulationQueryHandlerTest extends TestCase
{
    public function testRetrievePopulations()
    {
        $container = new Container();
        $handler = $container->get(PopulationQueryHandler::class);

        $response = $handler->execute();

        $this->assertEquals(json_encode($response), $this->response());
    }

    private function response()
    {
        return '[{"name":"Employees","fields":[{"type":"text","required":true,"isUnique":true,"multi":false,"sensitive":false,"name":"employeeid","displayName":"Employee ID","isUniqueAcrossPopulation":true},{"type":"text","required":true,"isUnique":false,"multi":false,"sensitive":false,"name":"fname","displayName":"First Name","isUniqueAcrossPopulation":false},{"type":"text","required":true,"isUnique":false,"multi":false,"sensitive":false,"name":"lname","displayName":"Last Name","isUniqueAcrossPopulation":false},{"type":"date","required":true,"isUnique":false,"multi":false,"sensitive":true,"name":"bdate","displayName":"Birth Date","isUniqueAcrossPopulation":false},{"type":"text","required":true,"isUnique":false,"multi":false,"sensitive":true,"name":"bplace","displayName":"Birth Place","isUniqueAcrossPopulation":false},{"type":"email","required":true,"isUnique":true,"multi":false,"sensitive":false,"name":"email","displayName":"Email Address","isUniqueAcrossPopulation":true}]},{"name":"External Contractors","fields":[{"type":"text","required":true,"isUnique":true,"multi":false,"sensitive":false,"name":"employeeid","displayName":"Employee ID","isUniqueAcrossPopulation":true},{"type":"text","required":true,"isUnique":true,"multi":false,"sensitive":false,"name":"contractorid","displayName":"Contractor ID","isUniqueAcrossPopulation":false},{"type":"text","required":true,"isUnique":false,"multi":false,"sensitive":false,"name":"fname","displayName":"First Name","isUniqueAcrossPopulation":false},{"type":"text","required":true,"isUnique":false,"multi":false,"sensitive":false,"name":"lname","displayName":"Last Name","isUniqueAcrossPopulation":false},{"type":"email","required":true,"isUnique":true,"multi":false,"sensitive":false,"name":"email","displayName":"Email Address","isUniqueAcrossPopulation":true}]}]';
    }
}
