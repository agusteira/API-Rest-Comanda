<?php

include_once "user.php";

class PersonalGastronomico extends User implements IEmpleados{

    public function __construct ($date, $estado, $tipo, $nombre, $clave, $cantOperaciones = 0){
        parent::__construct($date, $estado, $tipo, $nombre, $clave, $cantOperaciones);
    }
    
    public static function crearEmpleado($nombre, $clave, $tipo = null){
        $date = date("Y-m-d H:i:s");
        $estado = "activo";
        
        $user = new PersonalGastronomico($date, $estado, $tipo, $nombre, $clave);
        return $user;
    }

    public function verProductosPendientes($sector){
        $datos = VentasPedidosADO::obtenerInstancia();
        $data = $datos->ObtenerProductosPorSectorYEstado($sector, "pendiente");
        return $data;
    }

    public function agregarTiempoEstimado($idPedido,$idProducto, $tiempoEstimado,$datosVentas){
        //agrega tiempo estimado al producto que va a preparar
        $datosVentas->ModificarTiempoEstimado($idPedido, $idProducto, $tiempoEstimado);

    }


    //Modifica los estados de la tabla ventas y pedidos, para ponerlas en preparacion
    //agrega tiempo estimado a la tabla ventas y suma una operacion
    public function tomarPedido($idPedido, $idProducto, $estado, $tiempoEstimado){
        $datosVentas = VentasPedidosADO::obtenerInstancia();
        $dataVentas = $datosVentas->ModificarEstado($idPedido, $idProducto, "en preparacion");
        $this->agregarTiempoEstimado($idPedido,$idProducto,$tiempoEstimado,$datosVentas);

        //Sumar operacion a la tabla usuarios

        $datosPedido = PedidosADO::obtenerInstancia();
        if ($datosPedido->ObtenerEstadoPorID($idPedido) == "pendiente"){
            $dataPedido = $datosPedido->ModificarEstadoPorID($idPedido, "en preparacion");
        }
        
        if ($dataPedido && $dataVentas){
            $retorno = true;
        }else{
            $retorno = false;
        }
        return $retorno;
    }

}