<?php

require_once "AccesoDatos.php"; 

class ProductosADO extends AccesoDatos
{
    protected static $objAccesoDatos; //Cada hija de acceso datos DEBE tener su propio ObjADO porque si no se pueden mezclas y provocar errores
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

    //SELECT
    public function traerTodosLosProductos(){
        //consulta
        $sql = "SELECT id, tipo, nombre, importe, tiempoEstimado FROM productos";
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
    public function obtenerImportePorNombre($nombre){
        //consulta
        $sql = "SELECT nombre, importe FROM productos WHERE nombre = :nombre";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':nombre', $nombre);
        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $importe = $result["importe"];
            return $importe;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function obtenerProductoPorNombre($nombre){
        //consulta
        $sql = "SELECT * FROM productos WHERE nombre = :nombre";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':nombre', $nombre);
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
    
    //INSERT
    public function altaProducto($producto)
    {
        $sql = "INSERT INTO `productos` (`tipo`, `nombre`, `importe`, `tiempoEstimado`) 
            VALUES (:tipo, :nombre, :importe, :tiempoEstimado)";

        $stmt = $this->prepararConsulta($sql);

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
    
    public function InsertarDesdeCSV($producto)
    {
        $sql = "INSERT INTO `productos`(`id`, `tipo`, `nombre`, `importe`, `tiempoEstimado`) 
            VALUES (:id, :tipo, :nombre, :importe, :tiempoEstimado)";

        $stmt = $this->prepararConsulta($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':id', $producto["id"]);
        $stmt->bindParam(':tipo', $producto["tipo"]);
        $stmt->bindParam(':nombre', $producto["nombre"]);
        $stmt->bindParam(':importe', $producto["importe"]);
        $stmt->bindParam(':tiempoEstimado', $producto["tiempoEstimado"]);

        // Ejecutar la consulta
        try {
            //echo "hola";
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

}