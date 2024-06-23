<?php

require_once "AccesoDatos.php"; 

class EncuestaADO extends AccesoDatos
{
    protected static $objAccesoDatos; //Cada hija de acceso datos DEBE tener su propio ObjADO porque si no se pueden mezclas y provocar errores
    private function __construct()
    {
        parent::__construct();
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new EncuestaADO();
        }
        return self::$objAccesoDatos;
    }
    //SELECT
    public function traerTodasLasEncuestas(){
        //consulta
        $sql = "SELECT * FROM encuesta";
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
        $sql = "SELECT * FROM encuesta WHERE codigoPedido = :codigo";
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

    //INSERT
    public function AltaOpinion($codigoPedido, $calMesa, $calRestaurante, $calMozo, $calCocinero, $comentarios)
    {
        $promedio = ($calCocinero + $calMesa +$calMozo + $calRestaurante) / 4;
        
        $sql = "INSERT INTO `encuesta` (`codigoPedido`, `mesa`, `restaurante`, `mozo`, `cocina`, `comentarios`, `promedio`) 
            VALUES (:codigoPedido, :mesa, :restaurante, :mozo, :cocina, :comentarios, :promedio)";

        $stmt = $this->prepararConsulta($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':codigoPedido', $codigoPedido);
        $stmt->bindParam(':mesa', $calMesa);
        $stmt->bindParam(':restaurante',$calRestaurante );
        $stmt->bindParam(':mozo', $calMozo);
        $stmt->bindParam(':cocina',$calCocinero);
        $stmt->bindParam(':comentarios', $comentarios);
        $stmt->bindParam(':promedio', $promedio);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }

}