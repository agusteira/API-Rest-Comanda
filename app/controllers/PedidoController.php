<?php

include_once "models/Pedido.php";
include_once "models/Mesas.php";
class PedidoController{
    public static function AltaPedido($request, $response, $args){
        //Aca hay que modificar el tema de la comprobacion del usuario creador y el tipo de producto ingresado, y hacerlo mediante Middleware
        $parametros = $request->getParsedBody();

        $nombreCliente = $parametros['nombreCliente'];
        $IDMesa = $parametros['IDmesa'];
        //$IDMozo = $parametros['IDMozo'];

        $jsonString = $parametros['productos'];
        $productos = json_decode($jsonString, true);
        
        //Data del usuario mediante su TOKEN para obtener el ID del mozo
        $dataUsuario = AutentificadorJWT::ObtenerData($request);
        
        if(Pedido::CrearPedido($nombreCliente,$IDMesa,$dataUsuario->id,$productos)){
            $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El pedido NO se pudo crear"));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ListaPedidos($request, $response, $args){
        $listaPedido = Pedido::traerTodo();
        $payload = json_encode(array("listaPedidos" => $listaPedido));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function RelacionarFoto($request, $response, $args){
        $uploadedFiles = $request->getUploadedFiles();
        $parametros = $request->getParsedBody();


        $foto = $uploadedFiles['foto'];
        $idPedido = $parametros["idPedido"];

        if(Pedido::RelacionarFoto($idPedido, $foto)){
            $payload = json_encode(array("mensaje" => "Foto relacionada con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "Error al relacionar la foto"));
        }

        // Verifica si hubo algún error al subir el archivo
        /*
        if ($photo->getError() === UPLOAD_ERR_OK) {
        }
        */
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function VerTiempoRestante($request, $response, $args){
        $parametros = $request->getQueryParams();

        $codigoMesa = $parametros['codigoMesa'];
        $codigoPedido = $parametros['codigoPedido'];
        
        if(Pedido::ObtenerTiempoRestante($codigoMesa, $codigoPedido) != false){
            $tiempoRestante = Pedido::ObtenerTiempoRestante($codigoMesa, $codigoPedido);
            $payload = json_encode(array("tiempoRestante" => $tiempoRestante));
        }else{
            $payload = json_encode(array("tiempoRestante" => "Tiempo estimado NO disponible"));
        }

        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}