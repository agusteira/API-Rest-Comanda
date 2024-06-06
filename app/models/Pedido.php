<?php

include_once "././db/ADO/PedidosADO.php";

class Pedido{
    public $_nombreCliente;
    public $_idMesa;
    public $_idMozo;
    public $_productos = [];
    public $_foto;
    public $_estado;
    public $_tiempoEsperaEstimado;
    public $_tiempoDemora;
    public $_importeFinal;
    public $_date;
    public $_comentarios;

    public function __construct ($nombreCliente, $IDMesa, $IDMozo, $estado, $tiempoDeEsperaEstimado, $tiempoDemora, $momentoEntrada){
        $this->_nombreCliente = $nombreCliente;
        $this->_idMesa = $IDMesa;
        $this->_idMozo = $IDMozo;
        $this->_estado = $estado;
        $this->_tiempoEsperaEstimado = $tiempoDeEsperaEstimado; 
        $this->_tiempoDemora = $tiempoDemora; 
        
        $this->_date = $momentoEntrada;
    }

    public static function CrearPedido($nombreCliente,$IDMesa,$IDMozo,$productosString){
        $estado = "pendiente";
        $tiempoDeEsperaEstimado = 0; //Esto lo tiene que modificar el personal de gastronomia
        $tiempoDemora = 0; //Esto se modifica cuando entre el cliente a ver el pedido
        $date = date("Y-m-d H:i:s"); //momento en el que se crea el pedido
        $pedido = new Pedido($nombreCliente,$IDMesa,$IDMozo, $estado, $tiempoDeEsperaEstimado, $tiempoDemora, $date);

        $productos = explode(",", $productosString);

        $pedido->_importeFinal = $pedido->calcularImporteFinal($productos);

        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->altaPedido($pedido);

        return true;
    }
    public function calcularImporteFinal($productos){
        $importe = 0;
        foreach ($productos as $producto){
            $datos = ProductosADO::obtenerInstancia();
            $importeProducto = $datos->obtenerImportePorNombre($producto);

            $importe += $importeProducto;
        }
        //Se conecta a la base de datos de ventas
        return $importe;
    }

    public static function traerTodo(){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->traerTodosLosPedidos();
        return $data;
    }
}