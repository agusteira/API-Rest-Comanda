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
    public static function DescargarCSV($request, $response, $args){
        $filePath = Mesas::TraerTodoEnCSV(); //Devuelve un csv
        return $response->withHeader('Content-Type', 'application/csv')
                        ->withHeader('Content-Disposition', 'attachment; filename="' . "Mesas" . "_". date("d-m-Y").".csv" . '"')
                        ->withHeader('Content-Length', filesize($filePath))
                        ->withBody(new \Slim\Psr7\Stream(fopen($filePath, 'r')));
    }

    public static function CargarCSV($request, $response, $args){
        $uploadedFiles = $request->getUploadedFiles();

        $archivoCSV = $uploadedFiles["archivo"];
        $filename = "Mesas" . "_" . date("d-m-Y");
        
        if(Mesas::CargarCSV($archivoCSV, $filename)){
            $payload = json_encode(array("mensaje" => "BASE DE DATOS ACTUALIZADA"));
        }
        else{
            $payload = json_encode(array("mensaje" => "NO se han producido CAMBIOS en la BASE DE DATOS"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
