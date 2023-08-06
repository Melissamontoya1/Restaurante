<!DOCTYPE html>
<html>
	<head>
		<!-- 	TITULO DEL SISTEMA -->
		<title>Demo Sistema Restaurante</title>
		<link rel="stylesheet" href="./style.css">

		<!-- 	TITULO DEL SISTEMA EN PANTALLA -->
		<h1 style="text-align:center;">SOFTWARE CARNICERIA</h1>

		<center>
			<!-- BOTONES DE INGRESO -->
			<a href="staff" >&nbsp;&nbsp;&nbsp;Empleado&nbsp;&nbsp;&nbsp;</a>
			<a href="admin">Administrador</a>
		</center>
	</head>
	<body>
		</div>
		<!-- LIBRERIAS DE JAVASCRIPT -->
		<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
	</body>
</html>






<?php 
include("../functions.php");
$max =  "
SELECT MAX(orderID) AS orderID
FROM tbl_order 
";

if ($orderResult = $sqlconnection->query($max)) {
                        //if no order
	if ($orderResult->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
	}

	else {
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$id=$orderRow['orderID'];
		}
	}
}

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
			$nombre_empresa=$fila2['nombre_empresa'];
			$direccion_empresa=$fila2['direccion_empresa'];
			$telefono_empresa=$fila2['telefono_empresa'];
			$resolucion=$fila2['resolucion'];
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

require __DIR__ . '/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
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

$nombre_impresora = "ImpresoraTermica";
$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);
$printer->setJustification(Printer::JUSTIFY_CENTER);

//detalles de la factura como iva/ subtotal y total
$subtotal = new item('Subtotal', number_format($total_factura,0));
$tax = new item('IVA', number_format($total_iva,0));
$total = new item('Total',number_format($total_factura,0), true);
/* Date is kept the same for testing */
$date = date('d/m/Y h:i:s A');


/* Inicializar la Impresora 
$logo = EscposImage::load("res/tonos.png", false);
$printer = new Printer($connector);

*/

//DATOS DE LA EMPRESA
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text($nombre_empresa."\n");
$printer -> text("NIT :".$id_empresa."\n");
$printer -> feed(2);
$printer -> selectPrintMode();
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> text("DIRECCION :\n");
$printer -> text($direccion_empresa."\n");
$printer -> text("TELEFONO :".$telefono_empresa."\n");
$printer -> text("FACT- # : " .$id."\n");
$printer -> feed();
//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
$printer -> text("--------------------------------");
$printer -> text("Forma de Pago : Efectivo\n");
$printer -> text("Fecha : ".$date."\n");
$printer -> text("--------------------------------");
/* Titulo del Recibo */
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("DETALLES DE LA COMPRA\n");
$printer -> setEmphasis(false);
/* Items para darle un espacio */
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);
/* FILAS*/
$printer -> text("KG/UND");
$printer -> text(" |Peso");
$printer -> text(" |Producto");
$printer -> text(" |Total");

/* Items para darle un espacio*/
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);

/* DATOS TRAIDOS DE LA CONSULTA SQL*/
$displayOrderQuery =  "
SELECT o.orderID, m.menuName, od.itemID,MI.menuItemName,od.quantity,o.status,mi.price ,o.order_date
FROM tbl_order o
INNER JOIN tbl_orderdetail od
ON o.orderID = od.orderID
INNER JOIN tbl_menuitem mi
ON od.itemID = mi.itemID
INNER JOIN tbl_menu m
ON mi.menuID = m.menuID
WHERE o.orderID='".$id."'";

if ($orderResult = $sqlconnection->query($displayOrderQuery)) {

	$currentspan = 0;
	$total = 0;

                        //if no order
	if ($orderResult->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
	}

	else {
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			
			$cantidad=$orderRow['quantity'];
			$fecha_venta=$orderRow['order_date'];
			$nombre=$orderRow['menuItemName'];
			$menu=$orderRow['menuName'];
			$precio=$orderRow['price'];
			$totalv=$cantidad*$precio;
			$total+=$totalv;


			/*Alinear a la izquierda para la cantidad y el nombre*/
			$printer->setJustification(Printer::JUSTIFY_LEFT);
			$printer->text("$ ".number_format($precio)." X ". $cantidad . " " . $nombre . "\n");

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

/* Impresion de item Iva y Total*/
$printer -> text($tax);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text("$".number_format($total));
$printer -> selectPrintMode();

/* Footer - Pie de pagina */
$printer -> feed(2);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Gracias por preferirnos\n");
$printer -> text($resolucion);
$printer -> feed(2);


/*Corte el recibo y abra el cajón o monedero */
$printer -> cut();
$printer -> Pulse();
/*Cerramos la sesion de la impresora*/
$printer -> close();

/*REDIRECIONAMOS AL INDEX
header("Location: ../index.php");
*/
}
}
?>