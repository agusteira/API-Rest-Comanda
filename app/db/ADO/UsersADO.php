<?php

require_once "AccesoDatos.php"; 

class UsersADO extends AccesoDatos
{
    protected static $objAccesoDatos; //Cada hija de acceso datos DEBE tener su propio ObjADO porque si no se pueden mezclas y provocar errores
    private function __construct()
    {
        parent::__construct();
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new UsersADO();
        }
        return self::$objAccesoDatos;
    }

    public function retornarTipoSegunID($IDuser)
    {
        $stmt = $this->objetoPDO->prepare("SELECT tipo FROM user WHERE ID = ?");
        $stmt->bindParam(1, $IDuser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function altaUsuario($usuario)
    {
        $sql = "INSERT INTO `user` (`tipo`, `fechaEntrada`, `cantOperaciones`, `estado`) 
            VALUES (:tipo, :fechaEntrada, :cantOperaciones, :estado)";

        $stmt = $this->objetoPDO->prepare($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':tipo', $usuario->_tipo);
        $stmt->bindParam(':fechaEntrada', $usuario->_date);
        $stmt->bindParam(':cantOperaciones',  $usuario->_cantOperaciones);
        $stmt->bindParam(':estado',  $usuario->_estado);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function traerTodosLosUsuarios(){
        //consulta
        $sql = "SELECT id, tipo, fechaEntrada, cantOperaciones, estado FROM User";
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