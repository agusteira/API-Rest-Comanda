<?php

include_once "././db/ADO/MesasADO.php";

class Mesas{
    public $_estado;

    public function __construct ($estado = "cerrada"){
        $this->_estado = $estado;
    }

    public static function CrearMesa(){
        $mesa = new Mesas();

        $datos = MesasADO::obtenerInstancia();
        
        $data = $datos->altaMesa($mesa);
        return $data;
    }

    public static function traerTodo(){
        $datos = MesasADO::obtenerInstancia();
        $data = $datos->traerTodasLasMesas();
        return $data;
    }

}