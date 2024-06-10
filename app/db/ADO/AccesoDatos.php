<?php
class AccesoDatos
{
    protected $objetoPDO;

    protected function __construct()
    {
        try {
            $contStr = "mysql:host=localhost; dbname=comanda";
            $user = "root";
            $pass = "";
            $this->objetoPDO = new PDO($contStr, $user, $pass);
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
            die();
        }
    }

    public function prepararConsulta($sql)
    {
        return $this->objetoPDO->prepare($sql);
    }

    public function obtenerUltimoId()
    {
        return $this->objetoPDO->lastInsertId();
    }

}