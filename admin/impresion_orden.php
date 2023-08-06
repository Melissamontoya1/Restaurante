<?php 
/* DATOS TRAIDOS DE LA CONSULTA SQL*/

include("../functions.php");


$id_orden= $_GET['orderID'];

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
		}
	}
}


$cliente =  "
SELECT o.orderID,o.prefijo,o.total,o.pago,o.devolucion,o.id_cliente_fk,m.menuName, od.itemID,mi.menuItemName,od.quantity,o.status,mi.price ,o.order_date,c.id_cliente,c.nombres,o.observacion_order,o.id_mesa_fk,me.id_mesa,me.tipo_mesa,me.numero_mesa
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
WHERE o.orderID='".$id_orden."'";

if ($orderResult = $sqlconnection->query($cliente)) {

	$currentspan = 0;
	$total = 0;

                        //if no order
	if ($orderResult->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momentoaa. </td></tr>";
	}

	else {
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$cliente=$orderRow['nombres'];
			$subtotal=$orderRow['subtotal'];
			$impoconsumo=$orderRow['impoconsumo'];
			$total_iva=$orderRow['total_iva'];
			$prefijo=$orderRow['prefijo'];
			$observacion_order=$orderRow['observacion_order'];
			$tipo_mesa=$orderRow['tipo_mesa'];
			$numero_mesa=$orderRow['numero_mesa'];

		}
		echo $cliente;
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


$nombre_impresora = "PIZZAH";
$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);
$printer->setJustification(Printer::JUSTIFY_CENTER);

//detalles de la factura como iva/ subtotal y total
$subtotal = new item('Subtotal', number_format($total_factura,0));
$impo = new item('Impoconsumo', number_format($impoconsumo,0));
$tax = new item('IVA', number_format($total_iva,0));
$totales = new item('Total');
/* Date is kept the same for testing */
$date = date('d/m/Y h:i:s A');


/* Inicializar la Impresora 
$logo = EscposImage::load("res/tonos.png", false);
$printer = new Printer($connector);

*/


//DATOS DE LA EMPRESA
//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text($nombre_empresa."\n");
$printer -> text("OBSERVACION :".$observacion_order."\n");
$printer -> text("--------------------------------");
$printer -> setEmphasis(false);

// $printer -> selectPrintMode();
// $printer -> setJustification(Printer::JUSTIFY_LEFT);
// $printer -> text("DIRECCION :\n");
// $printer -> text($direccion_empresa."\n");
// $printer -> text("TELEFONO :".$telefono_empresa."\n");
// $printer -> text("FACT- # :".$prefijo."-" .$id_orden."\n");
$printer -> feed();
//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
$printer -> text("--------------------------------");
// $printer -> text("Forma de Pago : Efectivo\n");
$printer -> text("Tipo Mesa : ".$tipo_mesa."\n");
$printer -> text("Numero Mesa : ".$numero_mesa."\n");
$printer -> text("Cajero : ".$_SESSION['username']."\n");
$printer -> text("--------------------------------");
/* Titulo del Recibo */
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("DETALLES DEL PEDIDO\n");
$printer -> setEmphasis(false);
/* Items para darle un espacio */
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);
/* FILAS*/
$printer -> text(" Producto");
$printer -> text(" |UND");
$printer -> text(" |Cant");
$printer -> text(" |Total");
/* Items para darle un espacio*/
/* Items para darle un espacio*/
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);

/* DATOS TRAIDOS DE LA CONSULTA SQL*/
$id_orden= $_GET['orderID'];
$displayOrderQuery =  "
SELECT o.orderID,o.prefijo,o.total,o.pago,o.devolucion,o.id_cliente_fk,m.menuName, od.itemID,mi.menuItemName,od.quantity,o.status,mi.price ,o.order_date,c.id_cliente,c.nombres
FROM tbl_order o
INNER JOIN tbl_orderdetail od
ON o.orderID = od.orderID
INNER JOIN tbl_menuitem mi
ON od.itemID = mi.itemID
INNER JOIN tbl_menu m
ON mi.menuID = m.menuID
INNER JOIN cliente c
ON o.id_cliente_fk = c.id_cliente
WHERE o.orderID='".$id_orden."'";

if ($orderResultp = $sqlconnection->query($displayOrderQuery)) {

	

                        //if no order
	if ($orderResultp->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
	}

	else {
		$currentspan = 0;
	$total = 0;
		while($orderRowp = $orderResultp->fetch_array(MYSQLI_ASSOC)) {
			$cliente=$orderRowp['nombres'];
			$totalt=$orderRowp['total'];
			$pago=$orderRowp['pago'];
			$devolucion=$orderRowp['devolucion'];
			$prefijo=$orderRowp['prefijo'];
			$cantidad=$orderRowp['quantity'];
			$fecha_venta=$orderRowp['order_date'];
			$nombre=$orderRowp['menuItemName'];
			$menu=$orderRowp['menuName'];
			$precio=$orderRowp['price'];
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

/* Impresion de item Subtotal*/
$printer -> setEmphasis(true);
$printer -> text($subtotal);
$printer -> setEmphasis(false);
$printer -> feed();
$printer -> feed(2);
/* Impresion de item Subtotal*/
$printer -> setEmphasis(true);
$printer -> text($impo);
$printer -> setEmphasis(false);
$printer -> feed();
$printer -> feed(2);
/* Impresion de item Iva y Total*/
$printer -> text($tax);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text($totales);
$printer -> text("$".number_format($total));
$printer -> selectPrintMode();

//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
// $printer -> feed(2);
// $printer -> text("--------------------------------");
// $printer -> text("Valor Recibido : ".number_format($pago)."\n");
// $printer -> text("Cambio : ".number_format($devolucion)."\n");
// $printer -> text("--------------------------------");
/* Footer - Pie de pagina */
$printer -> feed(2);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
//$printer -> text("Gracias por preferirnos\n");
$printer -> text($resolucion);
$printer -> feed(2);


/*Corte el recibo y abra el cajón o monedero */
$printer -> cut();
$printer -> Pulse();
/*Cerramos la sesion de la impresora*/
$printer -> close();

/*REDIRECIONAMOS AL INDEX
header("Location: ../index.php");*/


header("Location: index.php");
?>