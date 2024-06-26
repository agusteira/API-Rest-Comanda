<?php

include_once "models/users/User.php";
include_once "models/users/Socio.php";

class UserController 
{
    public static function AltaUsuario($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        $tipoAlta = $parametros['tipo'];
        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];

        if(Socio::crearUsuario($tipoAlta, $nombre, $clave)){
            $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El usuario NO se pudo crear"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ListaUsuarios($request, $response, $args){
        $listaUsers = User::traerTodosLosUsuarios();
        $payload = json_encode(array("listaUsuario" => $listaUsers));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function LoginUsuarios($request, $response, $args){
        //Verifica, contraseña, nombre y estado; Y ademas te crea un token con datos necesarios

        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $contraseña = $parametros['clave'];
        $listaUsers = User::traerTodosLosUsuarios();
        foreach($listaUsers as $user){
            if($user["nombre"] == $nombre && $contraseña == $user["clave"] && $user["estado"] == "activo"){
                
                $data = array(
                    "id" => $user["id"],
                    "tipo" => $user["tipo"],
                    "nombre" => $user["nombre"],
                );

                $token = AutentificadorJWT::CrearToken($data);
                $payload = json_encode(array('jwt' => $token));
                break;
            }
            else{
                $payload = json_encode(array("error" => "Usuario o contraseña invalido"));
            }
        }

        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
        //$response->withHeader('Content-Type', 'application/json');
    }

    public static function SuspenderUsuario($request, $response, $args)
    {
        //$parametros = $request->getParsedBody();
        //var_dump($parametros);
        $idUsuario = $args['id'];
        

        if(Socio::SuspenderUsuario($idUsuario)){
            $payload = json_encode(array("mensaje" => "Usuario SUSPENDIDO con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El usuario NO se pudo SUSPENDER"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function BorrarUsuario($request, $response, $args)
    {
        var_dump($args);
        $idUsuario = $args['id'];

        if(Socio::BorrarUsuario($idUsuario)){
            $payload = json_encode(array("mensaje" => "Usuario BORRADO con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El usuario NO se pudo BORRAR"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function DescargarCSV($request, $response, $args){
        $filePath = User::TraerTodoEnCSV(); //Devuelve un csv
        return $response->withHeader('Content-Type', 'application/csv')
                        ->withHeader('Content-Disposition', 'attachment; filename="' . "Usuarios" . "_". date("d-m-Y").".csv" . '"')
                        ->withHeader('Content-Length', filesize($filePath))
                        ->withBody(new \Slim\Psr7\Stream(fopen($filePath, 'r')));
    }

    public static function CargarCSV($request, $response, $args){
        $uploadedFiles = $request->getUploadedFiles();

        $archivoCSV = $uploadedFiles["archivo"];
        $filename = "Usuarios" . "_" . date("d-m-Y");
        
        if(User::CargarCSV($archivoCSV, $filename)){
            $payload = json_encode(array("mensaje" => "BASE DE DATOS ACTUALIZADA"));
        }
        else{
            $payload = json_encode(array("mensaje" => "NO se han producido CAMBIOS en la BASE DE DATOS"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerIngresoAlSistema($request, $response, $args){
        $parametros = $request->getQueryParams();
        
        $fecha1 = $parametros['fecha1'];

        if (isset($parametros['fecha2'])){
            $fecha2 = $parametros['fecha2'];
            $listaUsers = User::ObtenerIngresoAlSistema($fecha1, $fecha2);
        }else{
            $listaUsers = User::ObtenerIngresoAlSistema($fecha1);
        }

        $payload = json_encode(array("listaUsuario" => $listaUsers));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerOperacionesPorSector($request, $response, $args){
        $parametros = $request->getQueryParams();

        $operacionesPorSector = User::ObtenerOperacionesPorSector();
        
        $payload = json_encode(array("operaciones por sector" => $operacionesPorSector));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerOperacionesPorSectorListado($request, $response, $args){
        $parametros = $request->getQueryParams();

        $operacionesPorSector = User::ObtenerOperacionesPorSectorListado();
        
        $payload = json_encode(array("operaciones por sector listado por usuarios" => $operacionesPorSector));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerOperacionPorUsuario($request, $response, $args){
        $parametros = $request->getQueryParams();

        $operacionesPorSector = User::ObtenerOperacionPorUsuario();
        
        $payload = json_encode(array("operaciones por usuarios" => $operacionesPorSector));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}