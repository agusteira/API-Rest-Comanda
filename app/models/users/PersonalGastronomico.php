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

    public static function verProductosPendientes($tipoProducto){
        $datos = VentasPedidosADO::obtenerInstancia();
        $data = $datos->ObtenerProductosPorSectorYEstado($tipoProducto, "pendiente");
        return $data;
    }

    public static function agregarTiempoEstimado($idPedido,$idProducto, $tiempoEstimado){
        //agrega tiempo estimado al producto que va a preparar
        $datosVentas = VentasPedidosADO::obtenerInstancia();
        $datosVentas->ModificarTiempoEstimado($idPedido, $idProducto, $tiempoEstimado);

    }


    //Modifica los estados de la tabla ventas y pedidos, para ponerlas en preparacion
    //agrega tiempo estimado a la tabla ventas y suma una operacion
    public static function TomarPedido($idPedido, $idProducto, $tiempoEstimado, $idUser){
        $dataVentas = self::ModificarEstado($idPedido, $idProducto, "en preparacion");
        self::agregarTiempoEstimado($idPedido,$idProducto,$tiempoEstimado);

        //Sumar operacion a la tabla usuarios
        $dataUser = UsersADO::obtenerInstancia();
        $dataUser->sumarOperacion($idUser);

        $datosPedido = PedidosADO::obtenerInstancia();
        if ($datosPedido->ObtenerEstadoPorID($idPedido) == "pendiente"){
            $datosPedido->ModificarEstadoPorID($idPedido, "en preparacion");
        }
        
        if ($dataVentas){
            $retorno = true;
        }else{
            $retorno = false;
        }
        return $retorno;
    }

    public static function ModificarEstado($idPedido, $idProducto, $estado){
        $datosVentas = VentasPedidosADO::obtenerInstancia();
        $dataVentas = $datosVentas->ModificarEstado($idPedido, $idProducto, $estado);
        return $dataVentas;
    }

}