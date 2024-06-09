<?php

include_once "models/Mesas.php";

class MesaController{

    public static function AltaMesa($request, $response, $args){
        //Aca hay que modificar el tema de la comprobacion del usuario creador y el tipo de producto ingresado, y hacerlo mediante Middleware

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
}