<?php

require_once __DIR__ . '/app/functions.php';
require_once 'vendor/autoload.php';

use App\Container;
use App\Controller\UserController;
use App\Controller\PopulationController;
use App\Controller\UserValuesController;

$container = new Container();

router('GET', '^/$', function() {
    echo 'It is alive!';
});

router('GET', '^/population$', function() use ($container) {
    $controller = $container->get(PopulationController::class);
    header('Content-Type: application/json');
    echo $controller->index();
});

router('POST', '^/population/(?<id>\d+)/user$', function($params) use ($container) {
    header('Content-Type: application/json');
    $json = json_decode(file_get_contents('php://input'), true);
    $controller = $container->get(UserController::class);
    header('Content-Type: application/json');
    echo $controller->store($params['id'], $json);
});

router('POST', '^/user/(?<id>\d+)/validate-values$', function($params) use ($container) {
    header('Content-Type: application/json');
    $json = json_decode(file_get_contents('php://input'), true);
    $controller = $container->get(UserValuesController::class);
    header('Content-Type: application/json');
    echo $controller->validate($params['id'], $json);
});

header("HTTP/1.0 404 Not Found");