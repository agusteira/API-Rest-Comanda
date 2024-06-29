<?php

include_once "models/users/User.php";
include_once "models/users/Socio.php";

class UserController 
{
    public static function AltaUsuario($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        $tipoAlta = $parametros['tipo'];
        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];

        if(Socio::crearUsuario($tipoAlta, $nombre, $clave)){
            $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El usuario NO se pudo crear"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ListaUsuarios($request, $response, $args){
        $listaUsers = User::traerTodosLosUsuarios();
        $payload = json_encode(array("listaUsuario" => $listaUsers));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function LoginUsuarios($request, $response, $args){
        //Verifica, contraseña, nombre y estado; Y ademas te crea un token con datos necesarios

        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $contraseña = $parametros['clave'];
        $listaUsers = User::traerTodosLosUsuarios();
        foreach($listaUsers as $user){
            if($user["nombre"] == $nombre && $contraseña == $user["clave"] && $user["estado"] == "activo"){
                
                $data = array(
                    "id" => $user["id"],
                    "tipo" => $user["tipo"],
                    "nombre" => $user["nombre"],
                );

                $token = AutentificadorJWT::CrearToken($data);
                $payload = json_encode(array('jwt' => $token));
                break;
            }
            else{
                $payload = json_encode(array("error" => "Usuario o contraseña invalido"));
            }
        }

        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
        //$response->withHeader('Content-Type', 'application/json');
    }

    public static function SuspenderUsuario($request, $response, $args)
    {
        //$parametros = $request->getParsedBody();
        //var_dump($parametros);
        $idUsuario = $args['id'];
        

        if(Socio::SuspenderUsuario($idUsuario)){
            $payload = json_encode(array("mensaje" => "Usuario SUSPENDIDO con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El usuario NO se pudo SUSPENDER"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function BorrarUsuario($request, $response, $args)
    {
        var_dump($args);
        $idUsuario = $args['id'];

        if(Socio::BorrarUsuario($idUsuario)){
            $payload = json_encode(array("mensaje" => "Usuario BORRADO con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El usuario NO se pudo BORRAR"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function DescargarCSV($request, $response, $args){
        $filePath = User::TraerTodoEnCSV(); //Devuelve un csv
        return $response->withHeader('Content-Type', 'application/csv')
                        ->withHeader('Content-Disposition', 'attachment; filename="' . "Usuarios" . "_". date("d-m-Y").".csv" . '"')
                        ->withHeader('Content-Length', filesize($filePath))
                        ->withBody(new \Slim\Psr7\Stream(fopen($filePath, 'r')));
    }

    public static function CargarCSV($request, $response, $args){
        $uploadedFiles = $request->getUploadedFiles();

        $archivoCSV = $uploadedFiles["archivo"];
        $filename = "Usuarios" . "_" . date("d-m-Y");
        
        if(User::CargarCSV($archivoCSV, $filename)){
            $payload = json_encode(array("mensaje" => "BASE DE DATOS ACTUALIZADA"));
        }
        else{
            $payload = json_encode(array("mensaje" => "NO se han producido CAMBIOS en la BASE DE DATOS"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerIngresoAlSistema($request, $response, $args){
        $parametros = $request->getQueryParams();
        
        $fecha1 = $parametros['fecha1'];

        if (isset($parametros['fecha2'])){
            $fecha2 = $parametros['fecha2'];
            $listaUsers = User::ObtenerIngresoAlSistema($fecha1, $fecha2);
        }else{
            $listaUsers = User::ObtenerIngresoAlSistema($fecha1);
        }

        $payload = json_encode(array("listaUsuario" => $listaUsers));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerOperacionesPorSector($request, $response, $args){
        $parametros = $request->getQueryParams();

        $operacionesPorSector = User::ObtenerOperacionesPorSector();
        
        $payload = json_encode(array("operaciones por sector" => $operacionesPorSector));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerOperacionesPorSectorListado($request, $response, $args){
        $parametros = $request->getQueryParams();

        $operacionesPorSector = User::ObtenerOperacionesPorSectorListado();
        
        $payload = json_encode(array("operaciones por sector listado por usuarios" => $operacionesPorSector));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerOperacionPorUsuario($request, $response, $args){
        $parametros = $request->getQueryParams();

        $operacionesPorSector = User::ObtenerOperacionPorUsuario();
        
        $payload = json_encode(array("operaciones por usuarios" => $operacionesPorSector));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public statiC function PDF($request, $response, $args){
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('Agustin Teira');
        $pdf->SetAuthor('Agustin Teira');
        $pdf->SetTitle('La COMANDITA');
        $pdf->SetSubject('La comanditaria');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "La comanda 1° Cuatri 2024 |" ." Programacion iii |".' V1.0', "Agustin Teira", array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('times', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        
        // ----------------------------------------------------------------------------------------
        $mesActual = date("Y/m");

        $mesaMasUsada = Mesas::ObtenerMesaMasUsada(date("Y-m" . "01"), date("Y-m-d"));
        $mesaMasUsada = $mesaMasUsada[0]["idMesa"];

        $productoMasPedido = Pedido::ObtenerMasVendido(date("Y-m" . "01"), date("Y-m-d"));

        $factuacionTotal = Pedido::ObtenerFacturacionTotal(date("Y-m" . "01"), date("Y-m-d"));
        $factuacionTotal = $factuacionTotal[0]["facturacion"];

        $CantPedidos = Pedido::ObtenerCantPedidos(date("Y-m" . "01"), date("Y-m-d"));
        $CantPedidos = $CantPedidos[0]["cantidad"];

        $cantVentas = Pedido::ObtenerCantVentas(date("Y-m" . "01"), date("Y-m-d"));
        $cantVentas = $cantVentas[0]["cantidad"];

        $usuarioMasTrabajador = User::ObtenerUsuarioMasTrabajador();
        // Set some content to print
        $html = <<<EOD
        <h1 style="text-align: center;">Resumen comandita $mesActual</h1>
        <p>Ventas totales: $cantVentas</p>
        <p>Cantidad de pedidos: $CantPedidos</p>
        <p>Facturacion total: $factuacionTotal $</p>
        <p>ID Mesa mas utilizada: $mesaMasUsada</p>
        <p>Producto mas pedido: $productoMasPedido</p>
        <p>Usuario mas trabajador: $usuarioMasTrabajador</p>
        <p>: </p>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // -------------------------------------------------------------------------------

        // Close and output PDF document
        $filename = "ComanditaResumen_" . date("Y-m") . "pdf";
        $pdf->Output($filename, 'I');

        return $response
        ->withHeader('Content-Type', 'application/pdf')
        ->withHeader('Content-Disposition', 'attachment; filename="filename.pdf"');
    }
}