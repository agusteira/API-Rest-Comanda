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
    //SELECT
    public function traerTodosLosPedidos(){
        //consulta
        $sql = "SELECT * FROM pedidos";
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

    public function TraerUnoPorCodigo($codigo){
        //consulta
        $sql = "SELECT * FROM pedidos WHERE codigo = :codigo";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':codigo', $codigo);

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

    public function ObtenerEstadoPorID($id){
        //consulta
        $sql = "SELECT estado FROM pedidos WHERE id = :id";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $result = $result["estado"];
            return $result;
        }catch (PDOException $e) {
            return false;
        }
    }

    public function ObtenerTiempoRestante($codigo){
        $pedido = $this->TraerUnoPorCodigo($codigo);
        $tiempoDeEsperaEstimado = $pedido["tiempoDeEsperaEstimado"];
        $estado  = $pedido["estado"];
        
        if ($tiempoDeEsperaEstimado != "00:00:00" && $estado != "servido"){ //Si ya esta servido el plato, se deshabilita la opcion de poder ver el tiempo de espera
            $retorno = $tiempoDeEsperaEstimado;
        }else{
            $retorno = false;
        }

        return $retorno;
    }

    //INSERT
    public function altaPedido($pedido)
    {
        $sql = "INSERT INTO `pedidos` (`nombreCliente`, `idMesa`, `idMozo`, `estado`, `tiempoDeEsperaEstimado`, `tiempoDeDemora`, `importeFinal`, `horaEntrada`, `codigo`) 
            VALUES (:nombreCliente, :idMesa, :idMozo, :estado, :tiempoDeEsperaEstimado, :tiempoDeDemora, :importeFinal, :horaEntrada, :codigo)";

        $stmt = $this->prepararConsulta($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':nombreCliente', $pedido->_nombreCliente);
        $stmt->bindParam(':idMesa', $pedido->_idMesa);
        $stmt->bindParam(':idMozo',  $pedido->_idMozo);
        $stmt->bindParam(':estado',  $pedido->_estado);
        $stmt->bindParam(':tiempoDeEsperaEstimado', $pedido->_tiempoEsperaEstimado);
        $stmt->bindParam(':tiempoDeDemora', $pedido->_tiempoDemora);
        $stmt->bindParam(':importeFinal',  $pedido->_importeFinal);
        $stmt->bindParam(':horaEntrada',  $pedido->_date);
        $stmt->bindParam(':codigo',  $pedido->_codigoAlfanumerico);

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
    public function ModificarEstadoPorID($id, $estado){
        //consulta
        $sql = "UPDATE pedidos SET estado = :estado WHERE id = :id";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

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

    public function RelacionarFoto($id, $rutaFoto){
        //consulta
        $sql = "UPDATE pedidos SET foto = :foto WHERE id = :id";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':foto', $rutaFoto, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            //ejecuta la consulta
            $stmt->execute();
            //Se fija si hubo cambios en la tabla
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