<?php

require_once __DIR__ . '/app/functions.php';
require_once 'vendor/autoload.php';

use App\Container;
use App\Controller\UserController;
use App\Controller\PopulationController;
use App\Controller\UserValuesController;

$container = new Container();

function set_common_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
}

router('GET', '^/$', function() {
    echo 'It is alive!';
});

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    set_common_headers();
    exit(0);
}

set_common_headers();

router('GET', '^/population$', function() use ($container) {
    $controller = $container->get(PopulationController::class);
    header('Content-Type: application/json');
    echo $controller->index();
});

router('POST', '^/population/(?<id>\d+)/user$', function($params) use ($container) {
    $json = json_decode(file_get_contents('php://input'), true);
    $controller = $container->get(UserController::class);
    header('Content-Type: application/json');
    echo $controller->store($params['id'], $json);
});

router('GET', '^/user/(?<id>\d+)$', function($params) use ($container) {
    $controller = $container->get(UserController::class);
    header('Content-Type: application/json');
    echo $controller->show($params['id']);
});

router('POST', '^/user/(?<id>\d+)/validate-values$', function($params) use ($container) {
    $json = json_decode(file_get_contents('php://input'), true);
    $controller = $container->get(UserValuesController::class);
    header('Content-Type: application/json');
    echo $controller->validate($params['id'], $json);
});

header("HTTP/1.0 404 Not Found");
