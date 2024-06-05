<?php

include_once "user.php";

class PersonalGastronomico extends User implements IEmpleados{
    public $_tipo;


    public function __construct ($date, $estado, $tipo, $cantOperaciones = 0){
        parent::__construct($date, $estado, $tipo, $cantOperaciones);
    }
    
    public static function crearEmpleado($tipo = null){
        $date = date("Y-m-d H:i:s");
        $estado = "activo";
        
        $user = new PersonalGastronomico($date, $estado, $tipo);
        return $user;
    }

    public function verProductosPendientes(){
        //ListarLosProductosPendientes
    }

    public function agregarTiempoEstimado($idPedido){
        //agrega tiempo estimado al producto que va a preparar
    }

}