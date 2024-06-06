<?php

include_once "models/Pedido.php";

class PedidoController{
    public static function AltaPedido($request, $response, $args){
        //Aca hay que modificar el tema de la comprobacion del usuario creador y el tipo de producto ingresado, y hacerlo mediante Middleware
        $parametros = $request->getParsedBody();

        $nombreCliente = $parametros['nombreCliente'];
        $IDMesa = $parametros['IDmesa'];
        $IDMozo = $parametros['IDMozo'];
        $productos = $parametros['productos'];

        if(Pedido::CrearPedido($nombreCliente,$IDMesa,$IDMozo,$productos)){
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
}