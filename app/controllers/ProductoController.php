<?php

include_once "models/Productos.php";

class ProductoController{

    public static function AltaProducto($request, $response, $args){
        //Aca hay que modificar el tema de la comprobacion del usuario creador y el tipo de producto ingresado, y hacerlo mediante Middleware
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
}