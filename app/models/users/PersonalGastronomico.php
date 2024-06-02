<?php

include "user.php";

class PersonalGastronomico extends User implements IEmpleados{

    public $_productosPendientes = [];
    public $_tipo;

    public function __construct ($id, $date, $active,$tipo,$cantOperaciones = 0){
        parent::__construct($id, $date, $active, $cantOperaciones);
        $this->_tipo = $tipo;
    }

    public static function crearEmpleado($tipo){
        $id = 0;//Obtener ultima id del SQL
        $date = date("d-m-Y H:i:s");
        $active = true;
        
        $user = new PersonalGastronomico($id, $date, $active, $tipo);
        return $user;
    }

    public function verProductosPendientes(){
        //ListarLosProductosPendientes
    }

    public function agregarTiempoEstimado($idPedido){
        //agrega tiempo estimado al producto que va a preparar
    }

}