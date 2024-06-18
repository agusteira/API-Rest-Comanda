<?php

include_once "models/Mesas.php";

class MesaController{

    public static function AltaMesa($request, $response, $args){

        if(Mesas::CrearMesa()){
            $payload = json_encode(array("mensaje" => "Mesa creado con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "La mesa NO se pudo crear"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ListaMesas($request, $response, $args){
        $listaMesas = Mesas::traerTodo();
        $payload = json_encode(array("listaMesas" => $listaMesas));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function CambiarEstadoMesa($request, $response, $args){
        //Mediante middleware comprobar que exista el estado de mesa, y que sea
        //los 3 posibles y NO se pueda cerrar mesas
        $parametros = $request->getParsedBody();

        $IDMesa = $parametros['IDmesa'];
        $estado = $parametros['estado'];

        if(Mesas::CambiarEstadoMesa($IDMesa, $estado)){
            $payload = json_encode(array("mensaje" => "Mesa ACTUALIZADA con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "La mesa NO se pudo ACTUALIZAR"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function CerrarMesa($request, $response, $args){
        //Mediante middleware comprobar que exista el estado de mesa, y que sea
        //los 3 posibles y NO se pueda cerrar mesas
        $parametros = $request->getParsedBody();

        $IDMesa = $parametros['IDmesa'];

        if(Mesas::CambiarEstadoMesa($IDMesa, "cerrada")){
            $payload = json_encode(array("mensaje" => "Mesa CERRADA con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "La mesa NO se pudo CERRAR"));
        };

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}