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

    //INSERT
    public function altaMesa($mesa)
    {
        $sql = "INSERT INTO `mesas` (`codigo`, `estado`, `idPedidoActual`) 
            VALUES (:codigo, :estado, :idPedidoActual)";

        $stmt = $this->prepararConsulta($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':codigo', $mesa->_codigo);
        $stmt->bindParam(':estado', $mesa->_estado);
        $stmt->bindParam(':idPedidoActual', $mesa->_idPedidoActual);


        // Ejecutar la consulta
        try {
            //echo "hola";
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    //UPDATE
    public function cambiarEstadoMesa($idMesa, $estado, $idPedido = null){
        //consulta

        $sql = "UPDATE mesas SET estado = :estado, idPedidoActual = :idPedidoActual WHERE id = :idMesa";
        $stmt = $this->prepararConsulta($sql);
        
        $stmt->bindParam(':idPedidoActual', $idPedido, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);

        try {
            var_dump($estado);
            var_dump($idMesa);
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