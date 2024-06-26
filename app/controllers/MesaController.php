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

    public static function ObtenerMayorImporte($request, $response, $args){
        $parametros = $request->getQueryParams();
        
        $fecha1 = $parametros['fecha1'];

        if (isset($parametros['fecha2'])){
            $fecha2 = $parametros['fecha2'];
            $mesaConMayorImporte = Mesas::ObtenerMayorImporte($fecha1, $fecha2);
        }else{
            $mesaConMayorImporte = Mesas::ObtenerMayorImporte($fecha1);
        }

        $payload = json_encode(array("mesaConMayorImporte" => $mesaConMayorImporte));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerMenorImporte($request, $response, $args){
        $parametros = $request->getQueryParams();
        
        $fecha1 = $parametros['fecha1'];

        if (isset($parametros['fecha2'])){
            $fecha2 = $parametros['fecha2'];
            $mesaConMenorImporte = Mesas::ObtenerMenorImporte($fecha1, $fecha2);
        }else{
            $mesaConMenorImporte = Mesas::ObtenerMenorImporte($fecha1);
        }

        $payload = json_encode(array("mesaConMenorImporte" => $mesaConMenorImporte));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerMejoresComentarios($request, $response, $args){
        $mesaConMejoresComentarios = Mesas::ObtenerMejoresComentarios();
        $payload = json_encode(array("mesaConMejoresComentarios" => $mesaConMejoresComentarios));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerPeoresComentarios($request, $response, $args){
        $mesaConPeoresComentarios = Mesas::ObtenerPeoresComentarios();
        $payload = json_encode(array("mesaConPeoresComentarios" => $mesaConPeoresComentarios));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerFacturacion($request, $response, $args){
        $parametros = $request->getQueryParams();
        
        
        $idMesa = $args['idMesa'];
        if (isset($parametros['fecha1'], $parametros['fecha2'])){
            $fecha1 = $parametros['fecha1'];
            $fecha2 = $parametros['fecha2'];
            $facturacionEntreFechas = Mesas::ObtenerFacturacion($idMesa, $fecha1, $fecha2);
        }else{
            $facturacionEntreFechas = Mesas::ObtenerFacturacion($idMesa);
        }

        $payload = json_encode(array("facturacion" => $facturacionEntreFechas));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerMesaMasUsada($request, $response, $args){
        $parametros = $request->getQueryParams();

        $fecha1 = $parametros['fecha1'];
        if (isset($parametros['fecha2'])){
            $fecha2 = $parametros['fecha2'];
            $mesaMasUsada = Mesas::ObtenerMesaMasUsada($fecha1, $fecha2);
        }else{
            $mesaMasUsada = Mesas::ObtenerMesaMasUsada($fecha1);
        }

        $payload = json_encode(array("mesaMasUsada" => $mesaMasUsada));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerMesaMenosUsada($request, $response, $args){
        $parametros = $request->getQueryParams();

        $fecha1 = $parametros['fecha1'];
        if (isset($parametros['fecha2'])){
            $fecha2 = $parametros['fecha2'];
            $mesaMenosUsada = Mesas::ObtenerMesaMenosUsada($fecha1, $fecha2);
        }else{
            $mesaMenosUsada = Mesas::ObtenerMesaMenosUsada($fecha1);
        }

        $payload = json_encode(array("mesaMenosUsada" => $mesaMenosUsada));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerMesaMasFacturo($request, $response, $args){
        $parametros = $request->getQueryParams();

        $fecha1 = $parametros['fecha1'];
        if (isset($parametros['fecha2'])){
            $fecha2 = $parametros['fecha2'];
            $mesaMasFacturo = Mesas::ObtenerMesaMasFacturo($fecha1, $fecha2);
        }else{
            $mesaMasFacturo = Mesas::ObtenerMesaMasFacturo($fecha1);
        }

        $payload = json_encode(array("mesaMasFacturo" => $mesaMasFacturo));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerMesaMenosFacturo($request, $response, $args){
        $parametros = $request->getQueryParams();

        $fecha1 = $parametros['fecha1'];
        if (isset($parametros['fecha2'])){
            $fecha2 = $parametros['fecha2'];
            $mesaMenosFacturo = Mesas::ObtenerMesaMenosFacturo($fecha1, $fecha2);
        }else{
            $mesaMenosFacturo = Mesas::ObtenerMesaMenosFacturo($fecha1);
        }

        $payload = json_encode(array("mesaMenosFacturo" => $mesaMenosFacturo));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    
    }
}