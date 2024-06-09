<?php

include_once "././db/ADO/ProductosADO.php";

class Productos{
    public $_tipo;
    public $_importe;
    public $_tiempoEstimado;
    public $_nombre;

    public function __construct ($tipo, $importe, $nombre,$tiempoEstimado = null){
        $this->_tipo = $tipo;
        $this->_importe = $importe;
        $this->_tiempoEstimado = $tiempoEstimado;
        $this->_nombre = $nombre;
    }
    public static function CrearProducto($tipo, $importe, $nombre,$tiempoEstimado = null){
        $producto = new Productos($tipo, $importe,$nombre, $tiempoEstimado);

        $datos = ProductosADO::obtenerInstancia();
        $data = $datos->altaProducto($producto);
        return $data;
    }
    public static function traerTodo(){
        $datos = ProductosADO::obtenerInstancia();
        $data = $datos->traerTodosLosProductos();
        return $data;
    }

}