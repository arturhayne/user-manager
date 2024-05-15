<?php

require_once __DIR__ . '/app/functions.php';
require_once 'vendor/autoload.php';

use App\Container;
use App\Controller\UserValuesController;

$container = new Container();

router('GET', '^/$', function() {
    echo 'It is alive!';
});

router('POST', '^/user/(?<id>\d+)/validate-values$', function($params) use ($container) {
    header('Content-Type: application/json');
    $json = json_decode(file_get_contents('php://input'), true);
    $controller = $container->get(UserValuesController::class);
    header('Content-Type: application/json');
    echo $controller->validate($params['id'], $json);
});

header("HTTP/1.0 404 Not Found");