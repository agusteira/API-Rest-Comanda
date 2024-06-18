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
$app->addBodyParsingMiddleware();

$app->post('/login', \UserController::class . ':LoginUsuarios');
/*
$app->group('/', function (RouteCollectorProxy $group) {
    $app->post('/login', \UserController::class . ':LoginUsuarios');
});
*/

//Ver estado del pedido de parte del cliente
//Crear comentarios


$app->group('/usuario', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UserController::class . ':ListaUsuarios');
    $group->post('[/]', \UserController::class . ':AltaUsuario');
    $group->put('/{id}', \UserController::class . ':SuspenderUsuario');
    $group->delete('/{id}', \UserController::class . ':BorrarUsuario');
})->add(\AuthMiddleware::class . ':verificarSocio')->add(\AuthMiddleware::class . ':verificarToken');

$app->group('/pedido', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':ListaPedidos');
    $group->post('[/]', \PedidoController::class . ':AltaPedido');
    $group->post('/RelacionarFoto', \PedidoController::class . ':RelacionarFoto');
})->add(\AuthMiddleware::class . ':verificarToken');

$app->group('/mesa', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':ListaMesas');
    $group->post('[/]', \MesaController::class . ':AltaMesa')->add(\AuthMiddleware::class . ':verificarSocio');
    $group->put('/actualizarEstado', \MesaController::class . ':CambiarEstadoMesa'); 
    $group->put('/cerrar', \MesaController::class . ':CerrarMesa')->add(\AuthMiddleware::class . ':verificarSocio');
})->add(\AuthMiddleware::class . ':verificarToken');

$app->group('/producto', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':ListaProductos');
    $group->get('/verProductosPendientes', \ProductoController::class . ':ListaProductosPendientes');
    $group->post('[/]', \ProductoController::class . ':AltaProducto')->add(\AuthMiddleware::class . ':verificarSocio');
    $group->put('/tomarProducto', \ProductoController::class . ':TomarProducto');
    $group->put('/terminarProducto', \ProductoController::class . ':TerminarProducto');
})->add(\AuthMiddleware::class . ':verificarToken');

$app->group('/cliente', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':VerTiempoRestante');   //Ver tiempo restante
    $group->post('[/]', \ProductoController::class . ':AltaProducto');              //Emitir opinion

});


$app->run();

// php -S localhost:666 -t app (para prender el servidor)

?>

