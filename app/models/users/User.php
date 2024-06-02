<?php

class User {

    public $_id;
    public $_date;
    public $_cantOperaciones;
    public $_estado;

    public function __construct ($id, $date, $estado ,$cantOperaciones = 0){
        $this->_id = $id;
        $this->_date = $date;
        $this->_cantOperaciones = $cantOperaciones;
        $this->_estado = $estado;
    }

    public function cambiarEstadoPedido($estado, $idPedido){
        /* 
            cambiar de estado el pedido
        */
    }

}