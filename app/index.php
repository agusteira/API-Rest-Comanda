<?php


require_once '../vendor/autoload.php';
require_once "controllers/UserController.php";
require_once "controllers/ProductoController.php";
require_once "controllers/MesaController.php";
require_once "controllers/PedidoController.php";
require_once 'utils/AutentificadorJWT.php';
require_once 'middlewares/AuthMiddleware.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;





$app = AppFactory::create();

$app->group('[/]', function (RouteCollectorProxy $group) {
    $group->post('/login', \UserController::class . ':LoginUsuarios');
});


$app->group('/usuario', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UserController::class . ':ListaUsuarios');
    $group->post('[/]', \UserController::class . ':AltaUsuario');
    
  })->add(\AuthMiddleware::class . ':verificarToken');

$app->group('/pedido', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':ListaPedidos');
    $group->post('[/]', \PedidoController::class . ':AltaPedido');
})->add(\AuthMiddleware::class . ':verificarToken');

$app->group('/mesa', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':ListaMesas');
    $group->post('[/]', \MesaController::class . ':AltaMesa');
})->add(\AuthMiddleware::class . ':verificarToken');

$app->group('/producto', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':ListaProductos');
    $group->post('[/]', \ProductoController::class . ':AltaProducto');
})->add(\AuthMiddleware::class . ':verificarToken');

$app->run();

// php -S localhost:666 -t app (para prender el servidor)

?>

