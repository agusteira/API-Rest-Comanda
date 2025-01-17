<?php

require_once "AccesoDatos.php"; 

class MesasADO extends AccesoDatos
{
    protected static $objAccesoDatos; //Cada hija de acceso datos DEBE tener su propio ObjADO porque si no se pueden mezclas y provocar errores
    private function __construct()
    {
        parent::__construct();
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new MesasADO();
        }
        return self::$objAccesoDatos;
    }
    //SELECT
    public function traerTodasLasMesas(){
        //consulta
        $sql = "SELECT * FROM mesas";
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

    public function traerUnaMesa($idMesa){
        //consulta
        $sql = "SELECT * FROM mesas WHERE id = :idMesa";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);
        $stmt->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
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
    public function altaMesa($mesa)
    {
        $sql = "INSERT INTO `mesas` (`codigo`, `estado`) 
            VALUES (:codigo, :estado)";

        $stmt = $this->prepararConsulta($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':codigo', $mesa->_codigo);
        $stmt->bindParam(':estado', $mesa->_estado);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function InsertarDesdeCSV($mesa)
    {
        $sql = "INSERT INTO `mesas` (`id`, `codigo`, `estado`, `idPedidoActual`) 
            VALUES (:id, :codigo, :estado, :idPedidoActual)";

        $stmt = $this->prepararConsulta($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':id', $mesa["id"]);
        $stmt->bindParam(':codigo', $mesa["codigo"]);
        $stmt->bindParam(':estado', $mesa["estado"]);
        $stmt->bindParam(':idPedidoActual', $mesa["idPedidoActual"]);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    //UPDATE
    public function cambiarEstadoMesa($idMesa, $estado, $idPedido = null){
        if ($estado != "cerrada" && $idPedido == null){
            $mesaDelPedido = self::traerUnaMesa($idMesa);
            $idPedido = $mesaDelPedido[0]["idPedidoActual"]; //al devolverme la mesa, me devuelve un array adentro de un array, y ahi estan los datos
        }

        //consulta

        $sql = "UPDATE mesas SET estado = :estado, idPedidoActual = :idPedidoActual WHERE id = :idMesa";
        $stmt = $this->prepararConsulta($sql);
        
        $stmt->bindParam(':idPedidoActual', $idPedido, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);

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

    public function cambiarEstadoMesaPorIDPedido($idPedido, $estado){

        $sql = "UPDATE mesas SET estado = :estado WHERE idPedidoActual = :idPedido";
        $stmt = $this->prepararConsulta($sql);
        
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':idPedido', $idPedido);

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