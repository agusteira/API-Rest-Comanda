<?php

include_once "././db/ADO/MesasADO.php";

class Mesas{
    public $_estado;
    public $_id;
    public $_idPedidoActual;
    public function __construct ($estado = "cerrada", $IdPedidoActual = 0){
        $this->_estado = $estado;
        $this->_idPedidoActual = $IdPedidoActual;
    }

    public static function CrearMesa(){
        $mesa = new Mesas();
        $mesa->generarIdAlfanumerico();

        $datos = MesasADO::obtenerInstancia();
        
        $data = $datos->altaMesa($mesa);
        return $data;
    }

    public static function traerTodo(){
        $datos = MesasADO::obtenerInstancia();
        $data = $datos->traerTodasLasMesas();
        return $data;
    }

    public function generarIdAlfanumerico($longitud = 5) {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        $this->_id = $codigo;
    }

}