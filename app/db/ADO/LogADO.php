<?php

require_once "AccesoDatos.php"; 

class LogADO extends AccesoDatos
{
    protected static $objAccesoDatos; //Cada hija de acceso datos DEBE tener su propio ObjADO porque si no se pueden mezclas y provocar errores
    private function __construct()
    {
        parent::__construct();
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new LogADO();
        }
        return self::$objAccesoDatos;
    }

    //SELECT
    public function traerTodosLosLog(){
        //consulta
        $sql = "SELECT * FROM `log`";
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
    public function traerUsuarioPorID($id){
        $stmt = $this->prepararConsulta("SELECT * FROM `log` WHERE idUsuario = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

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
    public function altaLog($idusuario, $ruta, $hora)
    {   
        $sql = "INSERT INTO `log` (`idUsuario`, `ruta`, `hora`) 
            VALUES (:idUsuario, :ruta, :hora)";
        
        $stmt = $this->prepararConsulta($sql);
        // Vincular los valores a los parámetros
        $stmt->bindParam(':idUsuario', $idusuario);
        $stmt->bindParam(':ruta', $ruta);
        $stmt->bindParam(':hora',  $hora);
        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "chauu";
            return false;
        }
    }
}