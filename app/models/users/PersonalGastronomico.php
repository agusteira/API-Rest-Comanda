<?php

include "user.php";

class PersonalGastronomico extends User implements IEmpleados{

    public $_productosPendientes = [];
    public $_tipo;


    public function __construct ($date, $estado, $tipo, $cantOperaciones = 0){
        parent::__construct($date, $estado, $tipo, $cantOperaciones);
    }
    
    public static function crearEmpleado($tipo = null){
        $date = date("Y-m-d H:i:s");
        $estado = "activo";
        
        $user = new PersonalGastronomico($date, $estado, "mozo");
        return $user;
    }

    public function verProductosPendientes(){
        //ListarLosProductosPendientes
    }

    public function agregarTiempoEstimado($idPedido){
        //agrega tiempo estimado al producto que va a preparar
    }

}