<?php

include_once "././db/ADO/ProductosADO.php";
include_once "models/Pedido.php";

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
    public static function TraerTodoEnCSV(){
        $data = self::traerTodo();
        $filename = "Productos" . "_". date("d-m-Y").".csv";
        return Pedido::GenerarCSV($filename, $data);
    }
    public static function CargarCSV($ArchivoCSV, $filename){
        $productos = Pedido::ConvertirCSVenArray($ArchivoCSV, $filename);
        $datos = ProductosADO::obtenerInstancia();
        $retorno = false;
        foreach ($productos as $producto){
            if($datos->InsertarDesdeCSV($producto)){
                $retorno = true;
            }
        }
        return $retorno;
    }

}