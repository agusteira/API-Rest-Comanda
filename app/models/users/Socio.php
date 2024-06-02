<?php

include "Mozo.php";
include "PersonalGastronomico.php";


class Socio extends User {

    public function __construct ($id, $date, $active, $cantOperaciones = 0){
        parent::__construct($id, $date, $active, $cantOperaciones);
    }

    public static function crearSocio(){
        $id = 0;//Obtener ultima id del SQL
        $date = date("d-m-Y H:i:s");
        $active = true;
        
        $user = new Socio($id, $date, $active);
        return $user;
    }

    public function crearUsuario($tipo){
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