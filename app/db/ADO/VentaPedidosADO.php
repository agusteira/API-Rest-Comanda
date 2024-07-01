<?php

require_once "AccesoDatos.php"; 

class VentasPedidosADO extends AccesoDatos
{
    protected static $objAccesoDatos; //Cada hija de acceso datos DEBE tener su propio ObjADO porque si no se pueden mezclas y provocar errores
    private function __construct()
    {
        parent::__construct();
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new VentasPedidosADO();
        }
        return self::$objAccesoDatos;
    }


    //SELECT
    public function TraerTodasLasVentas(){
        //consulta
        $sql = "SELECT * FROM ventas";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);
        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function ObtenerTodosLosProductosDeUnPedido($idPedido){
        //consulta
        $sql = "SELECT * FROM ventas WHERE idPedido = :idPedido";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_STR);

        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function ObtenerProductosPorSectorYEstado($tipo, $estado){
        if ($tipo == "socio"){
            $sql = "SELECT * FROM ventas WHERE estado = :estado";
            $stmt = $this->prepararConsulta($sql);
        }else{
            $sql = "SELECT * FROM ventas WHERE tipoProducto = :tipo AND estado = :estado";
            $stmt = $this->prepararConsulta($sql);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        }
        
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);

        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function ObtenerProductoMasAtrasadoPorID($idPedido){
        //consulta 
        $sql = "SELECT MAX(horaListaParaServirEstimada) as fecha_hora_maxima FROM ventas WHERE idPedido = :idPedido";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_STR);

        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function TraerMasVendido($fecha1, $fecha2){
        //consulta
        if ($fecha2 == null){
            $sql = "SELECT idProducto, COUNT(*) as cantidad FROM ventas WHERE DATE(horaListaParaServirEstimada) = ? GROUP BY idProducto ORDER BY cantidad DESC LIMIT 1 ";
            $stmt = $this->prepararConsulta($sql);
        }else{
            $sql = "SELECT idProducto, COUNT(*) as cantidad FROM ventas WHERE DATE(horaListaParaServirEstimada) BETWEEN ? AND ? GROUP BY idProducto ORDER BY cantidad DESC LIMIT 1 ";
            $stmt = $this->prepararConsulta($sql);
            $stmt->bindParam(2, $fecha2);
        }
        $stmt->bindParam(1, $fecha1);
        //prepara la consulta
        
        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function TraerMenosVendido($fecha1, $fecha2){
        //consulta
        if ($fecha2 == null){
            $sql = "SELECT idProducto, COUNT(*) as cantidad FROM ventas WHERE DATE(horaListaParaServirEstimada) = ? GROUP BY idProducto ORDER BY cantidad ASC LIMIT 1 ";
            $stmt = $this->prepararConsulta($sql);
        }else{
            $sql = "SELECT idProducto, COUNT(*) as cantidad FROM ventas WHERE DATE(horaListaParaServirEstimada) BETWEEN ? AND ? GROUP BY idProducto ORDER BY cantidad ASC LIMIT 1 ";
            $stmt = $this->prepararConsulta($sql);
            $stmt->bindParam(2, $fecha2);
        }
        $stmt->bindParam(1, $fecha1);
        //prepara la consulta
        
        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function TraerCantVentas($fecha1, $fecha2){
        if ($fecha2 == null){
            $sql = "SELECT COUNT(*) as cantidad FROM ventas WHERE DATE(horaListaParaServirEstimada) = ?";
            $stmt = $this->prepararConsulta($sql);
        }else{
            $sql = "SELECT COUNT(*) as cantidad FROM ventas WHERE DATE(horaListaParaServirEstimada) BETWEEN ? AND ?";
            $stmt = $this->prepararConsulta($sql);
            $stmt->bindParam(2, $fecha2);
        }
        $stmt->bindParam(1, $fecha1);
        //prepara la consulta
        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function TraerTipoProducto($idPedido, $idProducto){
        $sql = "SELECT tipoProducto FROM ventas WHERE idPedido = :idPedido AND idProducto = :idProducto ";

        $stmt = $this->prepararConsulta($sql);
        $stmt->bindParam(':idPedido', $idPedido);
        $stmt->bindParam(':idProducto', $idProducto);

        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    //INSERT
    public function altaVenta($pedido, $producto, $cantidad)
    {
        $sql = "INSERT INTO `ventas` (`idPedido`, `idProducto`, `cantidad`, `tipoProducto`,`estado`) 
            VALUES (:idPedido, :idProducto, :cantidad, :tipoproducto, :estado)";

        $stmt = $this->prepararConsulta($sql);

        $estado = "pendiente";
        // Vincular los valores a los parámetros
        $stmt->bindParam(':idPedido', $pedido->_id);
        $stmt->bindParam(':idProducto', $producto["id"]);
        $stmt->bindParam(':cantidad',  $cantidad);
        $stmt->bindParam(':tipoproducto',  $producto["tipo"]);
        $stmt->bindParam(':estado', $estado);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            $retorno = true;
        } catch (PDOException $e) {
            $retorno = false;
        }
        return $retorno;
    }

    public function InsertarDesdeCSV($producto)
    {
        $sql = "INSERT INTO `ventas`(`id`, `idPedido`, `idProducto`, `cantidad`, `tipoProducto`, `tiempoEstimado`, `estado`, `horaListaParaServirEstimada`)
            VALUES (:id, :idPedido, :idProducto, :cantidad, :tipoProducto, :tiempoEstimado, :estado, :horaListaParaServirEstimada)";

        $stmt = $this->prepararConsulta($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':id', $producto["id"]);
        $stmt->bindParam(':idPedido', $producto["idPedido"]);
        $stmt->bindParam(':idProducto', $producto["idProducto"]);
        $stmt->bindParam(':cantidad', $producto["cantidad"]);
        $stmt->bindParam(':tipoProducto', $producto["tipoProducto"]);
        $stmt->bindParam(':tiempoEstimado', $producto["tiempoEstimado"]);
        $stmt->bindParam(':estado', $producto["estado"]);
        $stmt->bindParam(':horaListaParaServirEstimada', $producto["horaListaParaServirEstimada"]);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    //UPDATE
    public function ModificarTiempoEstimado($idPedido, $idProducto, $tiempoEstimado){
        //calcular horalistaparaservirestimada con HORA ACTUAL y TIEMPO ESTIMADO
        $horaListaParaServirEstimada = new DateTime();
        list($hours, $minutes, $seconds) = explode(':', $tiempoEstimado); // Tiempo a sumar
        $interval = new DateInterval("PT{$hours}H{$minutes}M{$seconds}S");
        $horaListaParaServirEstimada->add($interval);// Sumar el intervalo al DateTime
        $horaListaParaServirEstimada = $horaListaParaServirEstimada->format('Y-m-d H:i:s'); //Formatear la hora

        //consulta
        $sql = "UPDATE ventas SET tiempoEstimado = :tiempoEstimado, horaListaParaServirEstimada = :horaListaParaServirEstimada WHERE idPedido = :idPedido AND idProducto = :idProducto ";
        $stmt = $this->prepararConsulta($sql);

        //Definir parametros de la consulta
        $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
        $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
        $stmt->bindParam(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_STR);
        $stmt->bindParam(':horaListaParaServirEstimada', $horaListaParaServirEstimada);

        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene las filas afectadas
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function ModificarEstado($idPedido, $idProducto, $estado){
        //consulta
        $sql = "UPDATE ventas SET estado = :estado WHERE idPedido = :idPedido AND idProducto = :idProducto ";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
        $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);


        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene las filas afectadas
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }




}