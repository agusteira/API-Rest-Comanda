<?php

require_once "user.php";
require_once "././interfaces/IEmpleados.php";

class Mozo extends User implements IEmpleados{

    public function __construct ($id, $date, $active, $cantOperaciones = 0){
        parent::__construct($id, $date, $active, $cantOperaciones);
    }
    
    public static function crearEmpleado($tipo = null){
        $id = 0;//Obtener ultima id del SQL
        $date = date("d-m-Y H:i:s");
        $active = true;
        
        $user = new Mozo($id, $date, $active);
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