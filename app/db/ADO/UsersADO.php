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

    //SELECT
    public function retornarTipoSegunID($IDuser)
    {
        $stmt = $this->prepararConsulta("SELECT tipo FROM user WHERE ID = ?");
        $stmt->bindParam(1, $IDuser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function traerTodosLosUsuarios(){
        //consulta
        $sql = "SELECT * FROM User";
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
        $stmt = $this->prepararConsulta("SELECT * FROM user WHERE ID = ?");
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

    public function TraerIngresosAlSistemaEntreFechas($fecha1, $fecha2){
        //consulta
        if ($fecha2 == null){
            $sql = "SELECT nombre, fechaEntrada FROM User WHERE DATE(fechaEntrada) = ?";
            $stmt = $this->prepararConsulta($sql);
        }else{
            $sql = "SELECT nombre, fechaEntrada FROM User WHERE DATE(fechaEntrada) BETWEEN ? AND ?";
            $stmt = $this->prepararConsulta($sql);
            $stmt->bindParam(2, $fecha2);
        }
        $stmt->bindParam(1, $fecha1);
        
        //prepara la consulta
        
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

    public function TraerOperacionesPorSector($sector){
        //consulta
        $sql = "SELECT SUM(cantOperaciones) as suma FROM User WHERE tipo = ?";
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(1, $sector);
        
        //prepara la consulta
        
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

    public function TraerOperacionesPorSectorListado($sector){
        //consulta
        $sql = "SELECT nombre, cantOperaciones FROM User WHERE tipo = ?";
        $stmt = $this->prepararConsulta($sql);
        $stmt->bindParam(1, $sector);
        
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

    public function TraerOperacionesPorUsuario(){
        //consulta
        $sql = "SELECT nombre, cantOperaciones FROM User";
        $stmt = $this->prepararConsulta($sql);

        try {
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    //INSERT
    public function altaUsuario($usuario)
    {
        $sql = "INSERT INTO `user` (`tipo`, `fechaEntrada`, `cantOperaciones`, `estado`, `nombre`, `clave`) 
            VALUES (:tipo, :fechaEntrada, :cantOperaciones, :estado, :nombre, :clave)";

        $stmt = $this->prepararConsulta($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':tipo', $usuario->_tipo);
        $stmt->bindParam(':fechaEntrada', $usuario->_date);
        $stmt->bindParam(':cantOperaciones',  $usuario->_cantOperaciones);
        $stmt->bindParam(':estado',  $usuario->_estado);
        $stmt->bindParam(':nombre',  $usuario->_nombre);
        $stmt->bindParam(':clave',  $usuario->_clave);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function InsertarDesdeCSV($producto)
    {
        $sql = "INSERT INTO `user`(`id`, `tipo`, `fechaEntrada`, `cantOperaciones`, `estado`, `nombre`, `clave`) 
            VALUES (:id, :tipo, :fechaEntrada, :cantOperaciones, :estado, :nombre, :clave)";

        $stmt = $this->prepararConsulta($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':id', $producto["id"]);
        $stmt->bindParam(':tipo', $producto["tipo"]);
        $stmt->bindParam(':fechaEntrada', $producto["fechaEntrada"]);
        $stmt->bindParam(':cantOperaciones', $producto["cantOperaciones"]);
        $stmt->bindParam(':estado', $producto["estado"]);
        $stmt->bindParam(':nombre', $producto["nombre"]);
        $stmt->bindParam(':clave', $producto["clave"]);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    //UPDATE
    public function suspenderUsuario($IDuser, $estado = "suspendido"){
        //consulta
        $sql = "UPDATE user SET estado = :estado WHERE id = :id";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':id', $IDuser, PDO::PARAM_INT);

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
    public function borrarUsuario($IDuser, $estado = "borrado"){
        //consulta
        $sql = "UPDATE user SET estado = :estado WHERE id = :id";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':id', $IDuser, PDO::PARAM_INT);

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
    public function SumarOperacion($IDuser){
        //consulta
        $sql = "UPDATE user SET cantOperaciones = cantOperaciones + 1 WHERE id = :id";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':id', $IDuser);

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
}