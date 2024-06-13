<?php

include_once "././db/ADO/UsersADO.php";
include_once "././db/ADO/PedidosADO.php";

class User {
    public $_id;
    public $_date;
    public $_cantOperaciones;
    public $_estado;
    public $_tipo;
    public $_nombre;
    public $_clave;

    public function __construct ($date, $estado ,$tipo,$nombre, $clave,$cantOperaciones = 0){
        $this->_date = $date;
        $this->_cantOperaciones = $cantOperaciones;
        $this->_estado = $estado;
        $this->_tipo = $tipo;
        $this->_nombre = $nombre;
        $this->_clave = $clave;
    }

    public static function comprobarTipoPorID($id, $tipo){
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->retornarTipoSegunID($id); 

        if ($data["tipo"] == $tipo){
            $retorno = true;
        }
        else{
            $retorno = false;
        }
        return $retorno;
    }

    public static function traerTodosLosUsuarios(){
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->traerTodosLosUsuarios();
        return $data;
    }

    public function cambiarEstadoPedido($estado, $idPedido){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->ModificarEstadoPorID($idPedido, $estado);
        return $data;
    }

    public static function obtenerUsuarioMedianteID($id){
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->traerUsuarioPorID($id);

        /*
        switch ($data["tipo"]){
            case "socio":
                $user = Socio();
                break;
            case "mozo":
                $user = new Mozo();
                break;
            case "cocinero":
                $user = new PersonalGastronomico();
                break;
            case "cervezero":
                $user = new PersonalGastronomico();
                break;
            case "bartender":
                $user = new PersonalGastronomico();
                break;
        }

        return $user;
        */
    }

}