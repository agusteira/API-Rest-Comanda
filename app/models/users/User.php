<?php

include_once "././db/ADO/UsersADO.php";
include_once "././db/ADO/PedidosADO.php";

class User {
    public $_id;
    public $_date;
    public $_cantOperaciones;
    public $_estado;
    public $_tipo;
    public $_nombre;
    public $_clave;

    public function __construct ($date, $estado ,$tipo,$nombre, $clave,$cantOperaciones = 0){
        $this->_date = $date;
        $this->_cantOperaciones = $cantOperaciones;
        $this->_estado = $estado;
        $this->_tipo = $tipo;
        $this->_nombre = $nombre;
        $this->_clave = $clave;
    }

    public static function comprobarTipoPorID($id, $tipo){
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->retornarTipoSegunID($id); 

        if ($data["tipo"] == $tipo){
            $retorno = true;
        }
        else{
            $retorno = false;
        }
        return $retorno;
    }

    public static function traerTodosLosUsuarios(){
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->traerTodosLosUsuarios();
        return $data;
    }

    public static function TraerTodoEnCSV(){
        $data = self::traerTodosLosUsuarios();
        $filename = "Usuarios" . "_". date("d-m-Y").".csv";
        return Pedido::GenerarCSV($filename, $data);
    }

    public function cambiarEstadoPedido($estado, $idPedido){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->ModificarEstadoPorID($idPedido, $estado);
        return $data;
    }

    public static function obtenerUsuarioMedianteID($id){
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->traerUsuarioPorID($id);

        /*
        switch ($data["tipo"]){
            case "socio":
                $user = Socio();
                break;
            case "mozo":
                $user = new Mozo();
                break;
            case "cocinero":
                $user = new PersonalGastronomico();
                break;
            case "cervezero":
                $user = new PersonalGastronomico();
                break;
            case "bartender":
                $user = new PersonalGastronomico();
                break;
        }

        return $user;
        */
    }
    public static function CargarCSV($ArchivoCSV, $filename){
        $usuarios = Pedido::ConvertirCSVenArray($ArchivoCSV, $filename);
        $datos = UsersADO::obtenerInstancia();
        $retorno = false;
        foreach ($usuarios as $usuario){
            if($datos->InsertarDesdeCSV($usuario)){
                $retorno = true;
            }
        }
        return $retorno;
    }

    public static function ObtenerIngresoAlSistema($fecha1, $fecha2 = null){
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->TraerIngresosAlSistemaEntreFechas($fecha1, $fecha2);
        return $data;
    }

    public static function ObtenerOperacionesPorSector(){
        $datos = UsersADO::obtenerInstancia();
        $operacionesSocios = $datos->TraerOperacionesPorSector("socio");
        $operacionesMozos =  $datos->TraerOperacionesPorSector("mozo");
        $operacionesCocineros =  $datos->TraerOperacionesPorSector("cocinero");
        $operacionesBartender =  $datos->TraerOperacionesPorSector("bartender");
        $operacionesCervezeros =  $datos->TraerOperacionesPorSector("cervezero");


        $data = array(
            "socios"=> $operacionesSocios[0]["suma"],
            "mozos"=> $operacionesMozos[0]["suma"],
            "cocineros"=> $operacionesCocineros[0]["suma"],
            "bartenders"=> $operacionesBartender[0]["suma"],
            "cervezeros"=> $operacionesCervezeros[0]["suma"],
        );
        return $data;
    }

    public static function ObtenerOperacionesPorSectorListado(){
        $datos = UsersADO::obtenerInstancia();
        $operacionesSocios = $datos->TraerOperacionesPorSectorListado("socio");
        $operacionesMozos =  $datos->TraerOperacionesPorSectorListado("mozo");
        $operacionesCocineros =  $datos->TraerOperacionesPorSectorListado("cocinero");
        $operacionesBartender =  $datos->TraerOperacionesPorSectorListado("bartender");
        $operacionesCervezeros =  $datos->TraerOperacionesPorSectorListado("cervezero");

        $data = array(
            "socios"=> $operacionesSocios,
            "mozos"=> $operacionesMozos,
            "cocineros"=> $operacionesCocineros,
            "bartenders"=> $operacionesBartender,
            "cervezeros"=> $operacionesCervezeros,
        );
        return $data;
    }

    public static function ObtenerOperacionPorUsuario(){
        $datos = UsersADO::obtenerInstancia();
        $data = $datos->TraerOperacionesPorUsuario();
        return $data;
    }

}