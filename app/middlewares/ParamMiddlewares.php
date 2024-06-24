<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

//Middlewares para verificar parametros
class ParamMiddlewares
{
    //Verificar los tipos de parametros
    public static function VerificarTipoUsuario(Request $request, RequestHandler $handler, $tipoUsuario){
        if ($tipoUsuario === "socio" || $tipoUsuario === "mozo" || $tipoUsuario === "cocinero" 
            || $tipoUsuario === "cervezero" || $tipoUsuario === "bartender")
        {
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Tipo de usuario INVALIDO")));
        }
        return $response;
    }

    public static function VerificarTipoEstadoMesa(Request $request, RequestHandler $handler, $estadoMesa){
        if ($estadoMesa === "con cliente comiendo" || $estadoMesa === "con cliente pagando" || $estadoMesa === "con cliente esperando")
        {
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Tipo de estado INVALIDO")));
        }
        return $response;
    }

    public static function VerificarExistenciaDePedidoPorCodigo(Request $request, RequestHandler $handler, $codigoPedido){
        if (Pedido::TraerPedidoPorCodigo($codigoPedido) != false)
        {
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Pedido INEXISTENTE")));
        }
        return $response;
    }

    public static function VerificarExistenciaDePedidoPorID(Request $request, RequestHandler $handler, $codigoPedido){
        if (Pedido::TraerPedidoPorID($codigoPedido) != false)
        {
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Pedido INEXISTENTE")));
        }
        return $response;
    }

    public static function VerificarInexisistenciaDeEncuesta(Request $request, RequestHandler $handler, $codigoPedido){
        if (Pedido::TraerEncuestaPorCodigo($codigoPedido) == false)
        {
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Encuesta YA EMITIDA")));
        }
        return $response;
    }

    //Verifcar si existen los parametros
    public static function AltaUsuario(Request $request, RequestHandler $handler){
        $parametros = $request->getParsedBody();

        if(isset($parametros["tipo"], $parametros["nombre"], $parametros["clave"])){
            $response = self::VerificarTipoUsuario($request, $handler, $parametros["tipo"]);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Parametros incorrectos")));
        }

        return $response;
    }

    public static function AltaPedido(Request $request, RequestHandler $handler){
        $parametros = $request->getParsedBody();

        if(isset($parametros["nombreCliente"], $parametros["IDmesa"], $parametros["productos"])){
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Parametros incorrectos")));
        }

        return $response;
    }

    public static function AltaProducto(Request $request, RequestHandler $handler){
        $parametros = $request->getParsedBody();

        if(isset($parametros["tipoProducto"], $parametros["importeProducto"], $parametros["tiempoEstimado"], $parametros["nombre"])){
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Parametros incorrectos")));
        }

        return $response;
    }

    public static function AltaEncuesta(Request $request, RequestHandler $handler){
        $parametros = $request->getParsedBody();

        if(isset($parametros["codigoMesa"], $parametros["codigoPedido"], $parametros["calMesa"], $parametros["calRestaurante"],
                $parametros["calMozo"], $parametros["calCocinero"], $parametros["comentarios"])){
            $response = self::VerificarExistenciaDePedidoPorCodigo($request, $handler, $parametros["codigoPedido"]);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Parametros incorrectos")));
        }

        return $response;
    }

    public static function RelacionarFoto(Request $request, RequestHandler $handler){
        $parametros = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();

        if(isset($uploadedFiles["foto"], $parametros["idPedido"])){
            $response = self::VerificarExistenciaDePedidoPorID($request, $handler, $parametros["codigoPedido"]);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Parametros incorrectos")));
        }

        return $response;
    }

    public static function CambiarEstadoMesa(Request $request, RequestHandler $handler){
        $parametros = $request->getParsedBody();

        if(isset($parametros["IDmesa"], $parametros["estado"])){
            $response = self::VerificarTipoEstadoMesa($request, $handler, $parametros["estado"]);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Parametros incorrectos")));
        }

        return $response;
    }

    public static function CerrarMesa(Request $request, RequestHandler $handler){
        $parametros = $request->getParsedBody();

        if(isset($parametros["IDmesa"])){
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Parametros incorrectos")));
        }

        return $response;
    }

    public static function TomarProducto(Request $request, RequestHandler $handler){
        $parametros = $request->getParsedBody();

        if(isset($parametros["idPedido"], $parametros["idProducto"], $parametros["tiempoEstimado"])){
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Parametros incorrectos")));
        }

        return $response;
    }

    public static function TerminarProducto(Request $request, RequestHandler $handler){
        $parametros = $request->getParsedBody();

        if(isset($parametros["idPedido"], $parametros["idProducto"])){
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Parametros incorrectos")));
        }

        return $response;
    }

    public static function VerTiempoRestante(Request $request, RequestHandler $handler){
        $parametros = $request->getQueryParams();

        if(isset($parametros["codigoMesa"], $parametros["codigoPedido"])){
            $response = self::VerificarExistenciaDePedidoPorCodigo($request, $handler, $parametros["codigoPedido"]);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Parametros incorrectos")));
        }

        return $response;
    }


}