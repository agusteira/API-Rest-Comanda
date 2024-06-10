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
    public function ObtenerTodosLosProductosDeUnPedido($idPedido){
        //consulta
        $sql = "SELECT * FROM ventas WHERE idPedido = :idPedido";
        //prepara la consulta
        $stmt = $this->objetoPDO->prepare($sql);

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
        //consulta
        $sql = "SELECT * FROM ventas WHERE tipo = :tipo AND estado = :estado";
        //prepara la consulta
        $stmt = $this->objetoPDO->prepare($sql);

        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
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

    //INSERT
    public function altaVenta($pedido, $producto)
    {
        $sql = "INSERT INTO `ventas` (`idPedido`, `idProducto`, `cantidad`, `tipoProducto`,`estado`) 
            VALUES (:idPedido, :idProducto, :cantidad, :tipoproducto, :estado)";

        $stmt = $this->objetoPDO->prepare($sql);

        $estado = "pendiente";
        // Vincular los valores a los parámetros
        $stmt->bindParam(':idPedido', $pedido->_id);
        $stmt->bindParam(':idProducto', $producto["id"]);
        $stmt->bindParam(':cantidad',  $producto["importe"]);
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

    //UPDATE
    public function ModificarTiempoEstimado($idPedido, $idProducto, $tiempoEstimado){
        //consulta
        $sql = "UPDATE ventas SET tiempoEstimado = :tiempoEstimado WHERE idPedido = :idPedido AND idProducto = :idProducto ";
        //prepara la consulta
        $stmt = $this->objetoPDO->prepare($sql);

        $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_STR);
        $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
        $stmt->bindParam(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_INT);

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
        $stmt = $this->objetoPDO->prepare($sql);

        $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_STR);
        $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);

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