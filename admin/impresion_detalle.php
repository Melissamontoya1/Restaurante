<?php 
/* DATOS TRAIDOS DE LA CONSULTA SQL*/

include("../functions.php");


$id_orden= $_GET['orderID'];


/* DATOS TRAIDOS DE LA CONSULTA SQL*/



$empresa =  "
SELECT MAX(id_empresa) AS id_empresa
FROM empresa 
";

if ($orderResult = $sqlconnection->query($empresa)) {
                        //if no order
    if ($orderResult->num_rows == 0) {

        echo "<tr><td class='text-center' colspan='7' >Actualmente no hay empresas registradas. </td></tr>";
    }

    else {
        while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {
            $id_empresa=$fila['id_empresa'];

        }
    }
}
$empresacon =  "
SELECT *
FROM empresa WHERE id_empresa='$id_empresa'
";

if ($orderResult = $sqlconnection->query($empresacon)) {
                        //if no order
    if ($orderResult->num_rows == 0) {

        echo "<tr><td class='text-center' colspan='7' >Actualmente no hay empresas registradas. </td></tr>";
    }

    else {
        while($fila2 = $orderResult->fetch_array(MYSQLI_ASSOC)) {
            $id_empresa=$fila2['id_empresa'];
            $nit_empresa=$fila2['nit_empresa'];
            $nombre_empresa=$fila2['nombre_empresa'];
            $direccion_empresa=$fila2['direccion_empresa'];
            $telefono_empresa=$fila2['telefono_empresa'];
            $resolucion=$fila2['resolucion'];
            $impresora=$fila2['nombre_impresora'];
             $tipo_negocio=$fila2['tipo_negocio'];
        }
    }
}

$prefijocon =  "
SELECT *
FROM tbl_order WHERE orderID='$id_orden'
";

if ($orderResult = $sqlconnection->query($prefijocon)) {
                        //if no order
    if ($orderResult->num_rows == 0) {

        echo "<tr><td class='text-center' colspan='7' >Actualmente no hay empresas registradas. </td></tr>";
    }

    else {
        while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {
            $prefijo=$fila['prefijo'];
        }
    }
}

$cliente =  "
SELECT o.orderID,o.prefijo,o.total,o.subtotal,o.total_desc, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
FROM tbl_order o
INNER JOIN tbl_orderdetail od
ON o.orderID = od.orderID
INNER JOIN tbl_menuitem mi
ON od.itemID = mi.itemID
INNER JOIN tbl_menu m
ON mi.menuID = m.menuID
INNER JOIN cliente c
ON o.id_cliente_fk = c.id_cliente
INNER JOIN mesa me
ON o.id_mesa_fk = me.id_mesa
INNER JOIN area_mesa a
ON me.id_area_fk=a.id_area
INNER JOIN tipo_mesa t 
ON me.id_tipo_fk= t.id_tipo

WHERE o.orderID='".$id_orden."'";

if ($orderResult = $sqlconnection->query($cliente)) {

    $currentspan = 0;
    $total = 0;

                        //if no order
    if ($orderResult->num_rows == 0) {


    }

    else {
        while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
            $identificacion=$orderRow['identificacion'];
            $telefono=$orderRow['telefono'];

            $cliente=$orderRow['nombres'];
            $nombre_caja=$orderRow['nombre_caja'];
            $numero_mesa=$orderRow['numero_mesa'];

        }
    }     
}


//INICIO DE FACTURA POS
function normaliza ($cadena){
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);

    return utf8_encode($cadena);
}

require __DIR__ . '../staff/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

/*
    Este ejemplo imprime un
    ticket de venta desde una impresora térmica
*/

/*
Este ejemplo imprime un Tiket de venta en una impresora de tickets
en Windows.
La impresora debe estar instalada como genérica y debe estar
compartida
 */

/*
Conectamos con la impresora
 */

/*
Aquí, en lugar de "POS-58" (que es el nombre de mi impresora)
escribe el nombre de la tuya. Recuerda que debes compartirla
desde el panel de control
 */
/* DATOS TRAIDOS DE LA CONSULTA SQL*/
$consultarOrden =  "
SELECT o.orderID,o.prefijo,o.total,o.subtotal,o.total_desc, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
FROM tbl_order o
INNER JOIN tbl_orderdetail od
ON o.orderID = od.orderID
INNER JOIN tbl_menuitem mi
ON od.itemID = mi.itemID
INNER JOIN tbl_menu m
ON mi.menuID = m.menuID
INNER JOIN cliente c
ON o.id_cliente_fk = c.id_cliente
INNER JOIN mesa me
ON o.id_mesa_fk = me.id_mesa
INNER JOIN area_mesa a
ON me.id_area_fk=a.id_area
INNER JOIN tipo_mesa t 
ON me.id_tipo_fk= t.id_tipo
WHERE o.orderID='".$id."'";

if ($orderResult = $sqlconnection->query($consultarOrden)) {

    $currentspan = 0;
    $total = 0;

     //if no order
    if ($orderResult->num_rows == 0) {


    }

    else {
        while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
            $subtotal=$orderRow['subtotal'];
            $impoconsumo=$orderRow['impoconsumo'];
            $total_iva=$orderRow['total_iva'];
        }
    }     
}

$nombre_impresora = "$impresora";
$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);
$printer->setJustification(Printer::JUSTIFY_CENTER);

//detalles de la factura como iva/ subtotal y total
$sub = new item('Subtotal');
$impo = new item('Iva', number_format($impoconsumo,0));
// $tax = new item('IVA', number_format($total_iva,0));
$totales = new item('Total');
/* Date is kept the same for testing */
$date = date('d/m/Y h:i:s A');


/* Inicializar la Impresora 
$logo = EscposImage::load("res/tonos.png", false);
$printer = new Printer($connector);

*/



//DATOS DE LA EMPRESA
$printer -> text("================================");
//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text($nombre_empresa."\n");
$printer -> text("NIT :".$nit_empresa."\n");
// $printer -> text("La Pintada- Antioquia\n");
// $printer -> text("================================");
// $printer -> text("NO SOMOS GRANDES CONTRIBUYENTES \n");
// $printer -> text("IVA Regimen Comun \n");
// $printer -> text("================================");
$printer -> setEmphasis(false);

$printer -> selectPrintMode();
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> text("DIRECCION :".$direccion_empresa."\n");
$printer -> text("TELEFONO :".$telefono_empresa."\n");

$printer -> feed();
//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
$printer -> text("================================");
$printer -> text("FACTURA DE VENTA POS- # :".$prefijo."-" .$id_orden."\n");
$printer -> text("Fecha : ".$date."\n");
$printer -> text("Cajero : ".$_SESSION['username']."\n");
$printer -> text("================================");
if ($tipo_negocio=="Restaurante") {
    $printer -> text("Mesa # :".$numero_mesa."\n");
}

$printer -> text("Cliente : ".$cliente."\n");
$printer -> text("NIT/CC : ".$identificacion."\n");
$printer -> text("Tel : ".$telefono."\n");


/* Titulo del Recibo */
$printer -> text("================================");
/* Titulo del Recibo */
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("DETALLES DE LA COMPRA\n");
$printer -> setEmphasis(false);

/* FILAS*/
$printer -> text(" Producto");
$printer -> text(" |UND");
$printer -> text(" |Cant");
$printer -> text(" |Total\n");
$printer -> text("================================");

/* Items para darle un espacio*/
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);

/* DATOS TRAIDOS DE LA CONSULTA SQL*/
$id_orden= $_GET['orderID'];
$displayOrderQuery =  "
SELECT o.orderID,o.prefijo,o.total,o.subtotal,o.total_desc, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
FROM tbl_order o
INNER JOIN tbl_orderdetail od
ON o.orderID = od.orderID
INNER JOIN tbl_menuitem mi
ON od.itemID = mi.itemID
INNER JOIN tbl_menu m
ON mi.menuID = m.menuID
INNER JOIN cliente c
ON o.id_cliente_fk = c.id_cliente
INNER JOIN mesa me
ON o.id_mesa_fk = me.id_mesa
INNER JOIN area_mesa a
ON me.id_area_fk=a.id_area
INNER JOIN tipo_mesa t 
ON me.id_tipo_fk= t.id_tipo
WHERE o.orderID='".$id_orden."'";

if ($orderResult = $sqlconnection->query($displayOrderQuery)) {

    $currentspan = 0;
    $total = 0;

                        //if no order
    if ($orderResult->num_rows == 0) {


    }

    else {
        while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
            $cliente=$orderRow['nombres'];
            $totalt=$orderRow['total'];
            $pago=$orderRow['pago'];
            $devolucion=$orderRow['devolucion'];
            $prefijo=$orderRow['prefijo'];
            $cantidad=$orderRow['quantity'];
            $fecha_venta=$orderRow['order_date'];
            $nombre=$orderRow['menuItemName'];
            $menu=$orderRow['menuName'];
            $precio=$orderRow['price'];
            $total_desc=$orderRow['total_desc'];
            $subtotal=$orderRow['subtotal'];
            $totalv=$cantidad*$precio;
            $total+=$totalv;


            /*Alinear a la izquierda para la cantidad y el nombre*/
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($nombre."\n"."$ ".number_format($precio)." X ". $cantidad );

            /*Y a la derecha precio unidad y precio total*/
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("  $ " .number_format($totalv). "\n");
        }
    }     
}


// /* Impresion de item Subtotal*/
if ($tipo_negocio=="Carniceria") {
$printer -> setEmphasis(true);
$printer -> text($sub);
$printer -> text("$".number_format($totalt));
$printer -> selectPrintMode();
$printer -> feed(1);

    // /* Impresion de item Subtotal*/
$printer -> setEmphasis(true);
$printer -> text($impo);
$printer -> setEmphasis(false);
$printer -> feed(2);
}

/* Impresion de item Iva y Total*/

$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text($totales);
$printer -> text("$".number_format($totalt));
$printer -> selectPrintMode();

//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
$printer -> feed(2);
//$printer -> text("---------------------------------------"."\n");
if ($total_desc=="0") {
    // code...
}else{
    $printer -> text("Descuento : ".number_format($total_desc)."\n");
}
if ($tipo_negocio==" 
Restaurante") {
    $Propinas2 =  "
SELECT *
FROM propinas WHERE cod_venta = '{$id_orden}' ORDER BY id_propina DESC";
if ($orderResultPro = $sqlconnection->query($Propinas2)) {
    if ($orderResultPro->num_rows == 0) {
      echo "ERROR";
  }else {
      $acumPropina=0;
      while($filaPro = $orderResultPro->fetch_array(MYSQLI_ASSOC)) {
        $id_propina=$filaPro['id_propina'];
        $fecha_propina=$filaPro['fecha_propina'];
        $valor_propina=$filaPro['valor_propina'];
        $acumPropina+=$valor_propina;
        $cod_venta=$filaPro['cod_venta'];
        $printer -> text("Propina : $ ".number_format($valor_propina)."\n");

    }
}     
}
}
if ($tipo_negocio=="Carniceria") {
    $printer -> text("================================");
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Discriminacion Tarifas IVA\n");
$printer -> setEmphasis(false);  
$printer -> text("================================");
$printer -> text("BASE EXENTA - 0%:    $ ".number_format($totalt)."\n");
$printer -> text("BASE GRAVADA  5%:    $0 \n");
$printer -> text("BASE GRAVADA 19%:    $0 \n");
$printer -> text("================================");

$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("DETALLES DEL PAGO\n");
$printer -> setEmphasis(false);
}

//DETALLES DEL PAGO

$pagosacum =  "
SELECT c.id_caja, c.nombre_caja, c.estado_caja,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, p.pago_orden, p.devuelto, p.fecha_pago
FROM pagos p
INNER JOIN tipo_caja c
ON p.id_caja_fk=c.id_caja
WHERE p.orderID='{$id_orden}'";
if ($orderResultPago = $sqlconnection->query($pagosacum)) {
                        //if no order
    if ($orderResultPago->num_rows == 0) {

        echo "ERROR";
    }else {
        $acumPago=0;
        while($orderpago = $orderResultPago->fetch_array(MYSQLI_ASSOC)) {
            $pago_orden=$orderpago['pago_orden'];
            $nombre_caja=$orderpago['nombre_caja'];
            $devuelto=$orderpago['devuelto'];
            $monto_recibido=$orderpago['monto_recibido'];
            $acumPago+=$pago_orden;

            $printer -> text("--------------------------------");
            $printer -> text("Metodo de Pago : $ ".$nombre_caja."\n");
            $printer -> text("Valor Recibido : $ ".number_format($monto_recibido)."\n");
            $printer -> text("Valor Pago : $ ".number_format($pago_orden)."\n");
            $printer -> text("Cambio : $ ".number_format($devuelto)."\n");
            $printer -> text("--------------------------------");
        }
    }
}

//$printer -> text("---------------------------------------"."\n");
/* Footer - Pie de pagina */
$printer -> feed(2);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Gracias por preferirnos\n");
$printer -> feed();
$printer -> text("================================");
$printer -> text($resolucion."\n");
$printer -> text("================================");
$printer -> feed();


/*Corte el recibo y abra el cajón o monedero */
$printer -> cut();
$printer -> Pulse();
/*Cerramos la sesion de la impresora*/
$printer -> close();

/*REDIRECIONAMOS AL INDEX*/
header("Location: index.php");

?> 