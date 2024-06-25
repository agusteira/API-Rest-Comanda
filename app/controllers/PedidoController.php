<?php

include_once "models/Pedido.php";
include_once "models/Mesas.php";
class PedidoController{
    public static function AltaPedido($request, $response, $args){
        //Aca hay que modificar el tema de la comprobacion del usuario creador y el tipo de producto ingresado, y hacerlo mediante Middleware
        $parametros = $request->getParsedBody();

        $nombreCliente = $parametros['nombreCliente'];
        $IDMesa = $parametros['IDmesa'];

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

    public static function EncuestaPedido($request, $response, $args){
        $parametros = $request->getParsedBody();

        $codigoMesa = $parametros['codigoMesa'];
        $codigoPedido = $parametros['codigoPedido'];
        $calMesa = $parametros['calMesa'];
        $calRestaurante = $parametros['calRestaurante'];
        $calMozo = $parametros['calMozo'];
        $calCocinero = $parametros['calCocinero'];
        $comentarios = $parametros['comentarios'];
        
        if(Pedido::Encuesta($codigoMesa, $codigoPedido, $calMesa, $calRestaurante, $calMozo, $calCocinero, $comentarios) != false){
            //$tiempoRestante = Pedido::ObtenerTiempoRestante($codigoMesa, $codigoPedido);
            $payload = json_encode(array("mensaje" => "Opinion emitida correctamente"));
        }else{
            $payload = json_encode(array("mensaje" => "NO se pudo emitir la opinion"));
        }

        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function DescargarCSV($request, $response, $args){
        $filePath = Pedido::TraerTodoEnCSV(); //Devuelve un csv
        return $response->withHeader('Content-Type', 'application/csv')
                        ->withHeader('Content-Disposition', 'attachment; filename="' . "Pedidos" . "_". date("d-m-Y").".csv" . '"')
                        ->withHeader('Content-Length', filesize($filePath))
                        ->withBody(new \Slim\Psr7\Stream(fopen($filePath, 'r')));
    }

    public static function DescargarVentasCSV($request, $response, $args){
        $filePath = Pedido::TraerVentasEnCSV(); //Devuelve un csv
        return $response->withHeader('Content-Type', 'application/csv')
                        ->withHeader('Content-Disposition', 'attachment; filename="' . "Ventas" . "_". date("d-m-Y").".csv" . '"')
                        ->withHeader('Content-Length', filesize($filePath))
                        ->withBody(new \Slim\Psr7\Stream(fopen($filePath, 'r')));
    }

    public static function DescargarEncuestaCSV($request, $response, $args){
        $filePath = Pedido::TraerEncuestaEnCSV(); //Devuelve un csv
        return $response->withHeader('Content-Type', 'application/csv')
                        ->withHeader('Content-Disposition', 'attachment; filename="' . "Encuestas" . "_". date("d-m-Y").".csv" . '"')
                        ->withHeader('Content-Length', filesize($filePath))
                        ->withBody(new \Slim\Psr7\Stream(fopen($filePath, 'r')));
    }

    public static function CargarCSV($request, $response, $args){
        $uploadedFiles = $request->getUploadedFiles();

        $archivoCSV = $uploadedFiles["archivo"];
        $filename = "Pedidos" . "_" . date("d-m-Y");
        
        if(Pedido::CargarCSV($archivoCSV, $filename)){
            $payload = json_encode(array("mensaje" => "BASE DE DATOS ACTUALIZADA"));
        }
        else{
            $payload = json_encode(array("mensaje" => "NO se han producido CAMBIOS en la BASE DE DATOS"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function CargarVentasCSV($request, $response, $args){
        $uploadedFiles = $request->getUploadedFiles();

        $archivoCSV = $uploadedFiles["archivo"];
        $filename = "Ventas" . "_" . date("d-m-Y");
        
        if(Pedido::CargarVentasCSV($archivoCSV, $filename)){
            $payload = json_encode(array("mensaje" => "BASE DE DATOS ACTUALIZADA"));
        }
        else{
            $payload = json_encode(array("mensaje" => "NO se han producido CAMBIOS en la BASE DE DATOS"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function CargarEncuestasCSV($request, $response, $args){
        $uploadedFiles = $request->getUploadedFiles();

        $archivoCSV = $uploadedFiles["archivo"];
        $filename = "Encuestas" . "_" . date("d-m-Y");
        echo "hola";
        if(Pedido::CargarEncuestasCSV($archivoCSV, $filename)){
            $payload = json_encode(array("mensaje" => "BASE DE DATOS ACTUALIZADA"));
        }
        else{
            $payload = json_encode(array("mensaje" => "NO se han producido CAMBIOS en la BASE DE DATOS"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}