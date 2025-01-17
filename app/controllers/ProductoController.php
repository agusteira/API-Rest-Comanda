<?php

include_once "models/Productos.php";

include_once "models/users/PersonalGastronomico.php";
include_once "utils/AutentificadorJWT.php";

class ProductoController{

    public static function AltaProducto($request, $response, $args){
        $parametros = $request->getParsedBody();

        $tipoProducto = $parametros['tipoProducto'];
        $importeProducto = $parametros['importeProducto'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        $nombre = $parametros['nombre'];


        if(Productos::CrearProducto($tipoProducto, $importeProducto, $nombre,$tiempoEstimado)){
            $payload = json_encode(array("mensaje" => "Producto creado con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El producto NO se pudo crear"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public static function ListaProductos($request, $response, $args){
        $listaProductos = Productos::traerTodo();
        $payload = json_encode(array("listaProductos" => $listaProductos));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public static function ListaProductosPendientes($request, $response, $args){
        $dataToken = AutentificadorJWT::ObtenerData($request);

        switch ($dataToken->tipo){
            case "socio":
                $tipoProducto = "socio";
                break;
            case "cocinero":
                $tipoProducto = "comidas";
                break;
            case "cervezero":
                $tipoProducto = "cervezas";
                break;
            case "bartender":
                $tipoProducto = "tragos y vinos";
                break;
            default:
                $tipoProducto = false;
                break;
        }
        
        if ($tipoProducto != false){
            $listaProductosPendientes = PersonalGastronomico::verProductosPendientes($tipoProducto);
            $payload = json_encode(array("listaPedidos" => $listaProductosPendientes));
        }else{
            $payload = json_encode(array("mensaje" => "Error, usuario no disponible"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public static function TomarProducto($request, $response, $args){
        $parametros = $request->getParsedBody();

        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];
        $tiempoEstimado = $parametros['tiempoEstimado'];

        //Data del usuario mediante su TOKEN
        $dataUsuario = AutentificadorJWT::ObtenerData($request);
        
        if(PersonalGastronomico::TomarPedido($idPedido, $idProducto, $tiempoEstimado, $dataUsuario->id)){
            $payload = json_encode(array("mensaje" => "Pedido tomado con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El pedido no se pudo tomar"));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public static function TerminarProducto($request, $response, $args){
        $parametros = $request->getParsedBody();

        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];


        if(PersonalGastronomico::ModificarEstado($idPedido, $idProducto, "listo para servir")){
            $payload = json_encode(array("mensaje" => "Producto listo para servir"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El producto NO se pudo crear"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ServirProducto($request, $response, $args){
        $parametros = $request->getParsedBody();

        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];

        if(PersonalGastronomico::ModificarEstado($idPedido, $idProducto, "servido")){
            $payload = json_encode(array("mensaje" => "Producto SERVIDO"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El producto NO se pudo SERVIR"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public static function DescargarCSV($request, $response, $args){
        $filePath = Productos::TraerTodoEnCSV(); //Devuelve un csv
        return $response->withHeader('Content-Type', 'application/csv')
                        ->withHeader('Content-Disposition', 'attachment; filename="' . "Productos" . "_". date("d-m-Y").".csv" . '"')
                        ->withHeader('Content-Length', filesize($filePath))
                        ->withBody(new \Slim\Psr7\Stream(fopen($filePath, 'r')));
    }
    public static function CargarCSV($request, $response, $args){
        $uploadedFiles = $request->getUploadedFiles();

        $archivoCSV = $uploadedFiles["archivo"];
        $filename = "Productos" . "_" . date("d-m-Y");
        
        if(Productos::CargarCSV($archivoCSV, $filename)){
            $payload = json_encode(array("mensaje" => "BASE DE DATOS ACTUALIZADA"));
        }
        else{
            $payload = json_encode(array("mensaje" => "NO se han producido CAMBIOS en la BASE DE DATOS"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}