<?php

include_once "././db/ADO/UsersADO.php";
include_once "././db/ADO/PedidosADO.php";

class User {
    public $_id;
    public $_date;
    public $_cantOperaciones;
    public $_estado;
    public $_tipo;

    public function __construct ($date, $estado ,$tipo, $cantOperaciones = 0){
        $this->_date = $date;
        $this->_cantOperaciones = $cantOperaciones;
        $this->_estado = $estado;
        $this->_tipo = $tipo;
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

}