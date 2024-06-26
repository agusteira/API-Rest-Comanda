<?php

include_once "././db/ADO/PedidosADO.php";
include_once "././db/ADO/ProductosADO.php";
include_once "././db/ADO/VentaPedidosADO.php";
include_once "././db/ADO/EncuestaADO.php";

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
    public $_codigoAlfanumerico;

    public function __construct ($nombreCliente, $IDMesa, $IDMozo, $estado, $tiempoDeEsperaEstimado, $tiempoDemora, $momentoEntrada, $codigoAlfanumerico){
        $this->_nombreCliente = $nombreCliente;
        $this->_idMesa = $IDMesa;
        $this->_idMozo = $IDMozo;
        $this->_estado = $estado;
        $this->_tiempoEsperaEstimado = $tiempoDeEsperaEstimado; 
        $this->_tiempoDemora = $tiempoDemora; 
        $this->_date = $momentoEntrada;
        $this->_codigoAlfanumerico = $codigoAlfanumerico;
    }

    //Crear
    public static function CrearPedido($nombreCliente,$IDMesa,$IDMozo,$arrayProductos){
        $estado = "pendiente";
        $tiempoDeEsperaEstimado = 0; //Esto lo tiene que modificar el personal de gastronomia
        $tiempoDemora = 0; //Esto se modifica cuando entre el cliente a ver el pedido
        $date = date("Y-m-d H:i:s"); //momento en el que se crea el pedido
        $codigoAlfanumerico = self::GenerarCodigoAlfanumerico();
        $pedido = new Pedido($nombreCliente,$IDMesa,$IDMozo, $estado, $tiempoDeEsperaEstimado, $tiempoDemora, $date, $codigoAlfanumerico);

        $pedido->_importeFinal = $pedido->calcularImporteFinal($arrayProductos);

        $datosPedido = PedidosADO::obtenerInstancia();
        $data = $datosPedido->altaPedido($pedido);

        $pedido->asignarId();
        $pedido->guardarVentaPorProducto($arrayProductos);
        Mesas::CambiarEstadoMesa($IDMesa, "con cliente esperando", $pedido->_id);

        return $data;
    }
    public function guardarVentaPorProducto($productos){
        $datosVentas = VentasPedidosADO::obtenerInstancia();
        foreach ($productos as $producto){
            $datoProductos = ProductosADO::obtenerInstancia();
            $productoInfo = $datoProductos->obtenerProductoPorNombre($producto["nombre"]);
            $datosVentas->altaVenta($this, $productoInfo, $producto["cantidad"]);
        }
    }
    public static function Encuesta($codigoMesa, $codigoPedido, $calMesa, $calRestaurante, $calMozo, $calCocinero, $comentarios){
        $datosEncuesta = EncuestaADO::obtenerInstancia();
        $data = $datosEncuesta->AltaOpinion($codigoPedido, $calMesa, $calRestaurante, $calMozo, $calCocinero, $comentarios);
        return $data;
    }

    public static function CargarCSV($ArchivoCSV, $filename){
        $pedidos = Pedido::ConvertirCSVenArray($ArchivoCSV, $filename);
        $datos = PedidosADO::obtenerInstancia();
        $retorno = false;
        foreach ($pedidos as $pedido){
            if($datos->InsertarDesdeCSV($pedido)){
                $retorno = true;
            }
        }
        return $retorno;
    }
    //leer
    public static function traerTodo(){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->traerTodosLosPedidos();
        return $data;
    }
    public static function TraerTodoEnCSV(){
        $data = self::traerTodo();
        $filename = "Pedidos" . "_". date("d-m-Y").".csv";
        return self::GenerarCSV($filename, $data);
    }
    public static function TraerPedidoPorCodigo($codigo){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->TraerUnoPorCodigo($codigo);
        return $data;
    }
    public static function TraerPedidoPorID($ID){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->TraerPedidoPorID($ID);
        return $data;
    }
    public static function ObtenerTiempoRestante($codigoMesa, $codigoPedido){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->ObtenerTiempoRestante($codigoPedido);
        return $data;
    }

    public static function ObtenerDemorados($fecha1, $fecha2 = null){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->TraerDemorados($fecha1, $fecha2);
        return $data;
    }

    public static function ObtenerCancelados($fecha1, $fecha2 = null){
        $datos = PedidosADO::obtenerInstancia();
        $data = $datos->TraerCancelados($fecha1, $fecha2);
        return $data;
    }
    
    //Modificar
    public static function RelacionarFoto($idPedido, $foto){
        $datos = PedidosADO::obtenerInstancia();

        $rutaTemporal =  $foto->getStream()->getMetadata('uri');
        $nombreImagen = "P" . $idPedido . ".jpg";
        $carpetaDestino = 'db/database/ImagenesDeLosClientes/';
        $rutaDestino = $carpetaDestino . $nombreImagen;
        
        if (move_uploaded_file($rutaTemporal, $carpetaDestino . $nombreImagen)) {
            $retorno = $datos->RelacionarFoto($idPedido, $rutaDestino);
        } else {
            $retorno = false;
        }
        return $retorno;
    }

    //Otras funciones
    public function calcularImporteFinal($productos){
        $importe = 0;
        foreach ($productos as $producto){
            $datos = ProductosADO::obtenerInstancia();
            $importeProducto = $datos->obtenerImportePorNombre($producto["nombre"]);
            $importeProducto = $importeProducto * $producto["cantidad"];
            $importe += $importeProducto;
        }
        return $importe;
    }

    public static function GenerarCodigoAlfanumerico($longitud = 5) {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = "";
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $codigo;
    }

    public function asignarId(){
        $datosPedido = PedidosADO::obtenerInstancia();
        $data = $datosPedido->obtenerUltimoId();
        $this->_id = $data;
    }

    public static function GenerarCSV($filename, $data){
        $filePath = "csv/descargados/" . $filename;

        $file = fopen($filePath, "w");

        fputcsv($file, array_keys($data[0]));
        foreach($data as $pedido){
            fputcsv($file, $pedido);
        }
        fclose($file);

        return $filePath;
    }

    public static function ConvertirCSVenArray($ArchivoCSV, $filename){
        $rutaTemporal =  $ArchivoCSV->getStream()->getMetadata('uri');
        $nombreArchivo = $filename . ".csv";
        $carpetaDestino = "csv/cargados/";
        
        move_uploaded_file($rutaTemporal, $carpetaDestino . $nombreArchivo);
        if (($handle = fopen($carpetaDestino . $nombreArchivo, "r")) !== FALSE) {
            $headers = fgetcsv($handle);
            // Leer el resto del archivo
            while (($row = fgetcsv($handle)) !== FALSE) {
                // Combinar los encabezados con los datos de la fila
                $csvData[] = array_combine($headers, $row);
            }
            fclose($handle);
        }
        return $csvData;
    }

        //Encuesta
    public static function TraerEncuestaPorCodigo($codigo){
        $datos = EncuestaADO::obtenerInstancia();
        $data = $datos->TraerUnoPorCodigo($codigo);
        return $data;
    }
    public static function TraerEncuestas(){
        $datos = EncuestaADO::obtenerInstancia();
        $data = $datos->traerTodasLasEncuestas();
        return $data;
    }
    public static function TraerEncuestaEnCSV(){
        $data = self::TraerEncuestas();
        $filename = "Encuestas" . "_". date("d-m-Y").".csv";
        return self::GenerarCSV($filename, $data);
    }

    public static function CargarEncuestasCSV($ArchivoCSV, $filename){
        $encuestas = Pedido::ConvertirCSVenArray($ArchivoCSV, $filename);
        $datos = EncuestaADO::obtenerInstancia();
        $retorno = false;
        foreach ($encuestas as $encuesta){
            if($datos->InsertarDesdeCSV($encuesta)){
                $retorno = true;
            }
        }
        return $retorno;
    }
    
        //Ventas
    public static function TraerVentas(){
        $datos = VentasPedidosADO::obtenerInstancia();
        $data = $datos->TraerTodasLasVentas();
        return $data;
    }
    public static function TraerVentasEnCSV(){
        $data = self::TraerVentas();
        $filename = "Ventas" . "_". date("d-m-Y").".csv";
        return self::GenerarCSV($filename, $data);
    }
    public static function CargarVentasCSV($ArchivoCSV, $filename){
        $ventas = Pedido::ConvertirCSVenArray($ArchivoCSV, $filename);
        $datos = VentasPedidosADO::obtenerInstancia();
        $retorno = false;
        foreach ($ventas as $venta){
            if($datos->InsertarDesdeCSV($venta)){
                $retorno = true;
            }
        }
        return $retorno;
    }

    public static function ObtenerMasVendido($fecha1, $fecha2 = null){
        $datosVentas = VentasPedidosADO::obtenerInstancia();
        $datosProductos = ProductosADO::obtenerInstancia();

        $data = $datosVentas->TraerMasVendido($fecha1, $fecha2);
        $nombreProducto = $datosProductos->ObtenerNombrePorId($data[0]["idProducto"]);

        return $nombreProducto["nombre"];
    }

    public static function ObtenerMenosVendido($fecha1, $fecha2 = null){
        $datosVentas = VentasPedidosADO::obtenerInstancia();
        $datosProductos = ProductosADO::obtenerInstancia();

        $data = $datosVentas->TraerMenosVendido($fecha1, $fecha2);
        $nombreProducto = $datosProductos->ObtenerNombrePorId($data[0]["idProducto"]);

        return $nombreProducto["nombre"];
    }

    


}