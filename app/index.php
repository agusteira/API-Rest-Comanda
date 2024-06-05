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


$app->post('/altaProducto', function (Request $request, Response $response, array $args) {
    ProductoController::AltaProducto($request, $response, $args);
    return $response;
});


$app->get('/ListarUsuario', function ($request, Response $response, array $args) {
    UserController::ListaUsuarios($request, $response, $args);
    return $response;    
});


$app->run();

// php -S localhost:666 -t app (para prender el servidor)

?>

