<?php

include_once "models/Productos.php";
include_once "models/users/PersonalGastronomico.php";

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

    public static function ListaProductosPendientes($request, $response, $args){
        $parametros = $request->getQueryParams();

        $tipoProducto = $parametros['tipoProducto'];
        
        $listaProductosPendientes = PersonalGastronomico::verProductosPendientes($tipoProducto);

        $payload = json_encode(array("listaPedidos" => $listaProductosPendientes));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function TomarProducto($request, $response, $args){
        //Aca hay que modificar el tema de la comprobacion del usuario creador y el tipo de producto ingresado, y hacerlo mediante Middleware
        $parametros = $request->getParsedBody();

        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];
        //$estado = $parametros['estado'];
        $tiempoEstimado = $parametros['tiempoEstimado'];


        if(PersonalGastronomico::TomarPedido($idPedido, $idProducto, $tiempoEstimado)){
            $payload = json_encode(array("mensaje" => "Producto creado con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El producto NO se pudo crear"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function TerminarProducto($request, $response, $args){
        //Aca hay que modificar el tema de la comprobacion del usuario creador y el tipo de producto ingresado, y hacerlo mediante Middleware
        $parametros = $request->getParsedBody();

        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];
        //$estado = $parametros['estado'];


        if(PersonalGastronomico::ModificarEstado($idPedido, $idProducto, "listo para servir")){
            $payload = json_encode(array("mensaje" => "Producto listo para servir"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El producto NO se pudo crear"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}