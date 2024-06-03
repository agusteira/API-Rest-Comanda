<?php
include_once "User.php";
include_once "Mozo.php";

class Socio extends User {

    public function __construct ($date, $estado, $tipo ,$cantOperaciones = 0){
        parent::__construct($date, $estado, $tipo ,$cantOperaciones);
    }

    public static function crearSocio(){
        $date = date("Y-m-d H:i:s");
        $estado = "activo";
        $user = new Socio($date, $estado, "socio");
        return $user;
    }

    public static function crearUsuario($tipo){
        switch ($tipo){
            case "socio":
                $user = Socio::CrearSocio();
                break;
            case "mozo":
                $user = Mozo::CrearEmpleado();
                break;
            case "cocinero":
                $user = PersonalGastronomico::CrearEmpleado($tipo);
                break;
            case "cervezero":
                $user = PersonalGastronomico::CrearEmpleado($tipo);
                break;
            case "bartender":
                $user = PersonalGastronomico::CrearEmpleado($tipo);
                break;
        }
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->altaUsuario($user);
        return $data;
    }

    public function suspenderUsuario($IDuser){
        /*
            Cambia el atributo Active de un usuario de True a False
            entrando su ID
        */
    }

    public function borrarUsuario($IDuser){
        /* Borra el usuario */
    }

    public function verMesas(){
        /*
        Lista todas las mesas y sus estados
         */
    }

    public function cerrarMesa(){
        /*
        Cambia el estado de la mesa y el estado del pedido
         */
    }

}