<?php

include_once "models/users/User.php";
include_once "models/users/Socio.php";

class UserController 
{
    public static function AltaUsuario($request, $response, $args)
    {
        //Aca hay que modificar el tema de la comprobacion del usuario SOCIO, y hacerlo mediante Middleware
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
        $parametros = $request->getParsedBody();
        
        $idUsuario = $parametros['idUsuario'];

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
        $parametros = $request->getParsedBody();
        
        $idUsuario = $parametros['idUsuario'];

        if(Socio::BorrarUsuario($idUsuario)){
            $payload = json_encode(array("mensaje" => "Usuario BORRADO con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El usuario NO se pudo BORRAR"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}