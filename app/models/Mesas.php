<?php

include_once "././db/ADO/MesasADO.php";

class Mesas{
    public $_estado;
    public $_codigo;
    public $_idPedidoActual;
    public function __construct ($estado = "cerrada", $IdPedidoActual = 0){
        $this->_estado = $estado;
        $this->_idPedidoActual = $IdPedidoActual;
    }
    public static function CrearMesa(){
        $mesa = new Mesas();
        $mesa->GenerarCodigoDeMesa();

        $datos = MesasADO::obtenerInstancia();
        $data = $datos->altaMesa($mesa);
        return $data;
    }
    public static function traerTodo(){
        $datos = MesasADO::obtenerInstancia();
        $data = $datos->traerTodasLasMesas();
        return $data;
    }
    public static function TraerTodoEnCSV(){
        $data = self::traerTodo();
        $filename = "Mesas" . "_". date("d-m-Y").".csv";
        return Pedido::GenerarCSV($filename, $data);
    }
    public function GenerarCodigoDeMesa($longitud = 3) {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = "M-";
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        $this->_codigo = $codigo;
    }
    public static function CambiarEstadoMesa($id, $estado, $idPedido = null){
        $datos = MesasADO::obtenerInstancia();
        $data = $datos->cambiarEstadoMesa($id, $estado, $idPedido);
        return $data;
    }
    public static function CargarCSV($ArchivoCSV, $filename){
        $mesas = Pedido::ConvertirCSVenArray($ArchivoCSV, $filename);
        $datos = MesasADO::obtenerInstancia();
        $retorno = false;
        foreach ($mesas as $mesa){
            if($datos->InsertarDesdeCSV($mesa)){
                $retorno = true;
            }
        }
        return $retorno;
    }

}