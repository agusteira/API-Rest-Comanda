<?php

class User {

    public $_id;
    public $_date;
    public $_cantOperaciones;
    public $_active;

    public function __construct ($id, $date, $active ,$cantOperaciones = 0){
        $this->_id = $id;
        $this->_date = $date;
        $this->_cantOperaciones = $cantOperaciones;
        $this->_active = $active;
    }

    public function cambiarEstadoPedido($estado, $idPedido){
        /* 
            cambiar de estado el pedido
        */
    }

}