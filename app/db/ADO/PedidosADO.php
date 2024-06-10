<?php

require_once "AccesoDatos.php"; 

class PedidosADO extends AccesoDatos
{
    protected static $objAccesoDatos; //Cada hija de acceso datos DEBE tener su propio ObjADO porque si no se pueden mezclar y provocar errores
    private function __construct()
    {
        parent::__construct();
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new PedidosADO();
        }
        return self::$objAccesoDatos;
    }
    public function altaPedido($pedido)
    {
        $sql = "INSERT INTO `pedidos` (`nombreCliente`, `idMesa`, `idMozo`, `estado`, `tiempoDeEsperaEstimado`, `tiempoDeDemora`, `importeFinal`, `horaEntrada`) 
            VALUES (:nombreCliente, :idMesa, :idMozo, :estado, :tiempoDeEsperaEstimado, :tiempoDeDemora, :importeFinal, :horaEntrada)";

        $stmt = $this->objetoPDO->prepare($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':nombreCliente', $pedido->_nombreCliente);
        $stmt->bindParam(':idMesa', $pedido->_idMesa);
        $stmt->bindParam(':idMozo',  $pedido->_idMozo);
        $stmt->bindParam(':estado',  $pedido->_estado);
        $stmt->bindParam(':tiempoDeEsperaEstimado', $pedido->_tiempoEsperaEstimado);
        $stmt->bindParam(':tiempoDeDemora', $pedido->_tiempoDemora);
        $stmt->bindParam(':importeFinal',  $pedido->_importeFinal);
        $stmt->bindParam(':horaEntrada',  $pedido->_date);
        // Ejecutar la consulta
        try {
            $stmt->execute();
            $retorno = true;
        } catch (PDOException $e) {
            $retorno = false;
        }
        return $retorno;
    }
    public function traerTodosLosPedidos(){
        //consulta
        $sql = "SELECT * FROM pedidos";
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
    public function ModificarEstadoPorID($id, $estado){
        //consulta
        $sql = "UPDATE pedidos SET estado = :estado WHERE id = :id";
        //prepara la consulta
        $stmt = $this->objetoPDO->prepare($sql);

        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    public function ObtenerEstadoPorID($id){
        //consulta
        $sql = "SELECT estado FROM pedidos WHERE id = :id";
        //prepara la consulta
        $stmt = $this->objetoPDO->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }catch (PDOException $e) {
            return false;
        }
    }

}