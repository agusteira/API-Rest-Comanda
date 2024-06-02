<?php

class Pedido{

    public $_id;
    public $_nombreCliente;
    public $_idMesa;
    public $_idMozo;
    public $_productos = [];
    public $_foto;
    public $_estado;
    public $_tiempoEsperaEstimado;
    public $_tiempoDemora;
    public $_importeFinal;
    public $_date;
    public $_comentarios;

    public function __construct ($nombreCliente, $IDMesa, $IDMozo, $productos){
        $this->_id = 0; //Hay que traer el ultimo ID de pedido de la base de datosy ponerlo aca
        $this->_nombreCliente = $nombreCliente;
        $this->_idMesa = $IDMesa;
        $this->_idMozo = $IDMozo;
        $this->_productos = $productos;
        $this->_estado = "pendiente";
        $this->_tiempoEsperaEstimado = 0; //Esto lo tiene que modificar el personal de gastronomia
        $this->_tiempoDemora = 0; //Esto se modifica cuando entre el cliente
        $this->_importeFinal = $this->calcularImporteFinal();
        $this->_date = date("d-m-Y H:i:s");
    }

    public function calcularImporteFinal(){
        //Calculamos el importe final con los productos y sus precios
        return 0;
    }
}