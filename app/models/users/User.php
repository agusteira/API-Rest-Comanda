<?php

include_once "././db/ADO/UsersADO.php";
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

    public function cambiarEstadoPedido($estado, $idPedido){
        /* 
            cambiar de estado el pedido
        */
    }

}