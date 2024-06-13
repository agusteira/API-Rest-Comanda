<?php
//include_once "User.php";
include_once "Mozo.php";
include_once "PersonalGastronomico.php";

class Socio extends User {

    public function __construct ($date, $estado, $tipo , $nombre, $clave,$cantOperaciones = 0){
        parent::__construct($date, $estado, $tipo, $nombre,$clave, $cantOperaciones);
    }

    public static function crearSocio($nombre, $clave){
        $date = date("Y-m-d H:i:s");
        $estado = "activo";
        $user = new Socio($date, $estado, "socio", $nombre, $clave);
        return $user;
    }

    public static function crearUsuario($tipo, $nombre, $clave){
        switch ($tipo){
            case "socio":
                $user = Socio::CrearSocio($nombre, $clave);
                break;
            case "mozo":
                $user = Mozo::CrearEmpleado($nombre, $clave);
                break;
            case "cocinero":
                $user = PersonalGastronomico::CrearEmpleado( $nombre, $clave, $tipo);
                break;
            case "cervezero":
                $user = PersonalGastronomico::CrearEmpleado($nombre, $clave, $tipo);
                break;
            case "bartender":
                $user = PersonalGastronomico::CrearEmpleado($nombre, $clave, $tipo);
                break;
        }
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->altaUsuario($user);
        return $data;
    }

    public static function SuspenderUsuario($IDuser){
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->suspenderUsuario($IDuser);
        return $data;
    }

    public static function BorrarUsuario($IDuser){
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->borrarUsuario($IDuser);
        return $data;
    }
}