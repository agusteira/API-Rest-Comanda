<?php


require_once '../vendor/autoload.php';
require_once "controllers/UserController.php";
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();



$app->post('/altaUsuario', function (Request $request, Response $response, array $args) {
    UserController::AltaUsuario($request, $response, $args);
    return $response;
});

$app->get('/', function ($request, Response $response, array $args) {
    
    $response->getBody()->write("hola");

    return $response;

    
});


$app->run();

// php -S localhost:666 -t app (para prender el servidor)

?>

