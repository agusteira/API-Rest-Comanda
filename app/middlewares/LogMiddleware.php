<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once "././db/ADO/LogADO.php";

class LogMiddleware
{
    public static function RegistrarMovimiento(Request $request, RequestHandler $handler){
        
        try{
            $header = $request->getHeaderLine('Authorization');
            $token = trim(explode("Bearer", $header)[1]);
            AutentificadorJWT::VerificarToken($token);

            $dataUsuario = AutentificadorJWT::ObtenerData($request);
            $idUsuario = $dataUsuario->id;

            $fullUrl = $_SERVER['REQUEST_URI'];
            $path = parse_url($fullUrl, PHP_URL_PATH);
            
            $hora = date("Y-m-d H:i:s");


            $dataLog = LogADO::obtenerInstancia();
            $dataLog->altaLog($idUsuario, $path, $hora);
            echo "ch";
        }catch(Exception $e){
            var_dump($e);
        }
        finally{
            $response = $handler->handle($request);
            return $response;
        }
    }
}