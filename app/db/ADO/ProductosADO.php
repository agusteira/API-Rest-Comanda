<?php

require_once "AccesoDatos.php"; 

class ProductosADO extends AccesoDatos
{
    private function __construct()
    {
        parent::__construct();
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new ProductosADO();
        }
        return self::$objAccesoDatos;
    }

    public function altaProducto($producto)
    {
        $sql = "INSERT INTO `productos` (`tipo`, `nombre`, `importe`, `tiempoEstimado`) 
            VALUES (:tipo, :nombre, :importe, :tiempoEstimado)";

        $stmt = $this->objetoPDO->prepare($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':tipo', $producto->_tipo);
        $stmt->bindParam(':nombre', $producto->_nombre);
        $stmt->bindParam(':importe',  $producto->_importe);
        $stmt->bindParam(':tiempoEstimado',  $producto->_tiempoEstimado);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            $retorno = true;
        } catch (PDOException $e) {
            $retorno = false;
        }
        return $retorno;
    }

    public function traerTodosLosProductos(){
        //consulta
        $sql = "SELECT id, tipo, nombre, importe, tiempoEstimado FROM productos";
        //prepara la consulta
        $stmt = $this->objetoPDO->prepare($sql);
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

}