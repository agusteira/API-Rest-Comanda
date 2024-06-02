<?php


require_once '../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();


$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Funciona!");
    return $response;
});

$app->get('/usuarios', function ($request, Response $response, array $args) {
    $params = $request->getQueryParams();

    $response->getBody()->write(json_encode($params));
    return $response;
});


$app->run();

// php -S localhost:666 -t app (para prender el servidor)

?>

