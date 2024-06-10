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

    public function altaMesa($mesa)
    {
        $sql = "INSERT INTO `mesas` (`id`, `estado`, `idPedidoActual`) 
            VALUES (:id, :estado, :idPedidoActual)";

        $stmt = $this->objetoPDO->prepare($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':id', $mesa->_id);
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

    public function traerTodasLasMesas(){
        //consulta
        $sql = "SELECT id, estado FROM mesas";
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

    public function cambiarEstadoMesa($idMesa, $estado, $idPedido = 0){
        //consulta
        if ($estado != "cerrada"){
            $sql = "UPDATE mesas SET estado = :estado WHERE idMesa = :idMesa";
            $stmt = $this->objetoPDO->prepare($sql);
        }else{
            // si la mesa se cambia a cerrada, se le cambia el id del pedido actual, asi no entra en conflicto
            $sql = "UPDATE mesas SET estado = :estado, idPedidoActual = :idPedidoActual WHERE idMesa = :idMesa";
            $stmt = $this->objetoPDO->prepare($sql);
            $stmt->bindParam(':idPedidoActual', $idPedido, PDO::PARAM_STR);
        }
        
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
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

}