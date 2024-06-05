<?php

require_once "AccesoDatos.php"; 

class MesasADO extends AccesoDatos
{
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
        $sql = "INSERT INTO `mesas` (`estado`) 
            VALUES (:estado)";

        $stmt = $this->objetoPDO->prepare($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':estado', $mesa->_estado);


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

}