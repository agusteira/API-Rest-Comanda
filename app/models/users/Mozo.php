<?php

require_once "user.php";
require_once "././interfaces/IEmpleados.php";

class Mozo extends User implements IEmpleados{

    public function __construct ($date, $estado, $tipo, $cantOperaciones = 0){
        parent::__construct($date, $estado, $tipo, $cantOperaciones);
    }
    
    public static function crearEmpleado($tipo = null){
        $date = date("Y-m-d H:i:s");
        $estado = "activo";
        
        $user = new Mozo($date, $estado, "mozo");
        return $user;
    }
    
    public function crearPedido($nombreCliente, $idMesa, $productos){
        $pedido = new Pedido($nombreCliente, $idMesa, $this->_id, $productos);
    }

    public function modificarEstadoMesa($idMesa){
        //Modifica el estado de la mesa
    }

    public function relacionarFoto($foto, $idPedido){
        //Relaciona la foto con el pedido
    }

}