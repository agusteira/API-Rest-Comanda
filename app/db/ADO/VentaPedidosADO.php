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

    public function altaVenta($pedido, $producto)
    {
        $sql = "INSERT INTO `ventas` (`idPedido`, `idProducto`, `cantidad`, `tipoProducto`) 
            VALUES (:idPedido, :idProducto, :cantidad, :tipoproducto)";

        $stmt = $this->objetoPDO->prepare($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':idPedido', $pedido->_id);
        $stmt->bindParam(':idProducto', $producto["id"]);
        $stmt->bindParam(':cantidad',  $producto["importe"]);
        $stmt->bindParam(':tipoproducto',  $producto["tipo"]);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            $retorno = true;
        } catch (PDOException $e) {
            $retorno = false;
        }
        return $retorno;
    }
    /*
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

    public function obtenerImportePorNombre($nombre){
        //consulta
        $sql = "SELECT nombre, importe FROM productos WHERE nombre = :nombre";
        //prepara la consulta
        $stmt = $this->objetoPDO->prepare($sql);

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
    
    */
}