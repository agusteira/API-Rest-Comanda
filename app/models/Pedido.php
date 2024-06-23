<?php

include_once "././db/ADO/PedidosADO.php";
include_once "././db/ADO/ProductosADO.php";
include_once "././db/ADO/VentaPedidosADO.php";
include_once "././db/ADO/EncuestaADO.php";

class Pedido{
    public $_id;
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
    public $_codigoAlfanumerico;

    public function __construct ($nombreCliente, $IDMesa, $IDMozo, $estado, $tiempoDeEsperaEstimado, $tiempoDemora, $momentoEntrada, $codigoAlfanumerico){
        $this->_nombreCliente = $nombreCliente;
        $this->_idMesa = $IDMesa;
        $this->_idMozo = $IDMozo;
        $this->_estado = $estado;
        $this->_tiempoEsperaEstimado = $tiempoDeEsperaEstimado; 
        $this->_tiempoDemora = $tiempoDemora; 
        $this->_date = $momentoEntrada;
        $this->_codigoAlfanumerico = $codigoAlfanumerico;
    }

    //Crear
    public static function CrearPedido($nombreCliente,$IDMesa,$IDMozo,$arrayProductos){
        $estado = "pendiente";
        $tiempoDeEsperaEstimado = 0; //Esto lo tiene que modificar el personal de gastronomia
        $tiempoDemora = 0; //Esto se modifica cuando entre el cliente a ver el pedido
        $date = date("Y-m-d H:i:s"); //momento en el que se crea el pedido
        $codigoAlfanumerico = self::GenerarCodigoAlfanumerico();
        $pedido = new Pedido($nombreCliente,$IDMesa,$IDMozo, $estado, $tiempoDeEsperaEstimado, $tiempoDemora, $date, $codigoAlfanumerico);

        $pedido->_importeFinal = $pedido->calcularImporteFinal($arrayProductos);

        $datosPedido = PedidosADO::obtenerInstancia();
        $data = $datosPedido->altaPedido($pedido);

        $pedido->asignarId();
        $pedido->guardarVentaPorProducto($arrayProductos);
        Mesas::CambiarEstadoMesa($IDMesa, "con cliente esperando", $pedido->_id);

        return $data;
    }
    public function guardarVentaPorProducto($productos){
        $datosVentas = VentasPedidosADO::obtenerInstancia();
        foreach ($productos as $producto){
            $datoProductos = ProductosADO::obtenerInstancia();
            $productoInfo = $datoProductos->obtenerProductoPorNombre($producto["nombre"]);
            $datosVentas->altaVenta($this, $productoInfo, $producto["cantidad"]);
        }
    }

    public static function Encuesta($codigoMesa, $codigoPedido, $calMesa, $calRestaurante, $calMozo, $calCocinero, $comentarios){
        $datosEncuesta = EncuestaADO::obtenerInstancia();
        $data = $datosEncuesta->AltaOpinion($codigoPedido, $calMesa, $calRestaurante, $calMozo, $calCocinero, $comentarios);
        return $data;
    }

    //leer
    public static function traerTodo(){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->traerTodosLosPedidos();
        return $data;
    }
    public static function ObtenerTiempoRestante($codigoMesa, $codigoPedido){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->ObtenerTiempoRestante($codigoPedido);
        return $data;
    }

    //Modificar
    public static function RelacionarFoto($idPedido, $foto){
        $datos = PedidosADO::obtenerInstancia();

        $rutaTemporal =  $foto->getStream()->getMetadata('uri');
        $nombreImagen = "P" . $idPedido . ".jpg";
        $carpetaDestino = 'db/database/ImagenesDeLosClientes/';
        $rutaDestino = $carpetaDestino . $nombreImagen;
        
        if (move_uploaded_file($rutaTemporal, $carpetaDestino . $nombreImagen)) {
            $retorno = $datos->RelacionarFoto($idPedido, $rutaDestino);
        } else {
            $retorno = false;
        }
        return $retorno;
    }

    //Otras funciones
    public function calcularImporteFinal($productos){
        $importe = 0;
        foreach ($productos as $producto){
            $datos = ProductosADO::obtenerInstancia();
            $importeProducto = $datos->obtenerImportePorNombre($producto["nombre"]);
            $importeProducto = $importeProducto * $producto["cantidad"];
            $importe += $importeProducto;
        }
        return $importe;
    }

    public static function GenerarCodigoAlfanumerico($longitud = 5) {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = "";
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $codigo;
    }

    public function asignarId(){
        $datosPedido = PedidosADO::obtenerInstancia();
        $data = $datosPedido->obtenerUltimoId();
        $this->_id = $data;
    }



}