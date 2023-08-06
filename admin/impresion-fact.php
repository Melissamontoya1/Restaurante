<?php


include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');


$order =  "
SELECT MAX(orderID) AS orderID
FROM tbl_order 
";

if ($orderResult = $sqlconnection->query($order)) {
                        //if no order
	if ($orderResult->num_rows == 0) {

		echo "ERROR";
	}

	else {
		while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$id_orden=$fila['orderID'];
		}
	}
}
$devo =  "
SELECT *
FROM tbl_order WHERE orderID='$id_orden'
";


if ($orderResult2 = $sqlconnection->query($devo)) {
                        //if no order
	if ($orderResult2->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay empresas registradas. </td></tr>";
	}

	else {
		while($fila2 = $orderResult2->fetch_array(MYSQLI_ASSOC)) {
			$pago=$fila2['pago'];
			$total=$fila2['total'];
			$devolucion=$fila2['devolucion'];
			$impoconsumo=$fila2['impoconsumo'];
			$subtotal=$fila2['subtotal'];
			$total_iva=$fila2['total_iva'];
		}
	}
}

?>


<div class="container-fluid">

	<!-- Breadcrumbs-->
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="index.php">Panel de Control</a>
		</li>
		<li class="breadcrumb-item active">Devuelta</li>
	</ol>

	<!-- Page Content -->
	<h1>INFORMACIÓN DEL PAGO</h1>
	<hr>
	<div class="row">
		<table class="table table-condensed">
			<tr>
				<th>Subtotal</th>
				<td>$ <?php echo number_format($subtotal); ?> </td>
			</tr>
			<tr>
				<th>Impoconsumo</th>
				<td>$ <?php echo number_format($impoconsumo); ?> </td>
			</tr>
			<tr>
				<th>Iva</th>
				<td>$ <?php echo number_format($total_iva); ?> </td>
			</tr>

			<tr  class="btn-success">
				<th>Total a Pagar</th>
				<td>$ <?php echo number_format($total); ?> </td>
			</tr>
			<tr>
				<th>Recibido</th>
				<td>$ <?php echo number_format($pago); ?> </td>
			</tr>
			<tr>
				<th>Devuelta</th>
				<td class="success">$ <?php echo number_format($devolucion); ?></td>
			</tr>
		</table>
	</div>

	<!-- /.container-fluid -->
</div>


<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
	<i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">¿Preparado para partir?</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">Seleccione "Cerrar Sesión" a continuación si está listo para finalizar su sesión actual.</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				<a class="btn btn-primary" href="../logout.php">Cerrar Sesión</a>
			</div>
		</div>
	</div>
</div>

<?php include ('includes/adminfooter.php');?>




<?php 
/* DATOS TRAIDOS DE LA CONSULTA SQL*/

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
			$nit_empresa=$fila2['nit_empresa'];
			$nombre_empresa=$fila2['nombre_empresa'];
			$direccion_empresa=$fila2['direccion_empresa'];
			$telefono_empresa=$fila2['telefono_empresa'];
			$resolucion=$fila2['resolucion'];
		}
	}
}

$prefijocon =  "
SELECT *
FROM tbl_order WHERE orderID='$id'
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
SELECT o.orderID,o.prefijo,o.total,o.pago,o.devolucion,o.id_cliente_fk,m.menuName, od.itemID,mi.menuItemName,od.quantity,o.status,mi.price ,o.order_date,c.id_cliente,c.nombres,c.identificacion
FROM tbl_order o
INNER JOIN tbl_orderdetail od
ON o.orderID = od.orderID
INNER JOIN tbl_menuitem mi
ON od.itemID = mi.itemID
INNER JOIN tbl_menu m
ON mi.menuID = m.menuID
INNER JOIN cliente c
ON o.id_cliente_fk = c.id_cliente
WHERE o.orderID='".$id."'";

if ($orderResult = $sqlconnection->query($cliente)) {

	$currentspan = 0;
	$total = 0;

                        //if no order
	if ($orderResult->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
	}

	else {
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$cliente=$orderRow['nombres'];
			$identificacion=$orderRow['identificacion'];
			
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
/* DATOS TRAIDOS DE LA CONSULTA SQL*/
$consultarOrden =  "
SELECT o.orderID,o.prefijo,o.total,o.pago,o.devolucion,o.id_cliente_fk,o.impoconsumo,o.total_iva,o.subtotal,m.menuName, od.itemID,mi.menuItemName,od.quantity,o.status,mi.price ,o.order_date,c.id_cliente,c.nombres
FROM tbl_order o
INNER JOIN tbl_orderdetail od
ON o.orderID = od.orderID
INNER JOIN tbl_menuitem mi
ON od.itemID = mi.itemID
INNER JOIN tbl_menu m
ON mi.menuID = m.menuID
INNER JOIN cliente c
ON o.id_cliente_fk = c.id_cliente
WHERE o.orderID='".$id."'";

if ($orderResult = $sqlconnection->query($consultarOrden)) {

	$currentspan = 0;
	$total = 0;

     //if no order
	if ($orderResult->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
	}

	else {
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$subtotal1=$orderRow['subtotal'];
			$impoconsumo=$orderRow['impoconsumo'];
			$total_iva=$orderRow['total_iva'];
		}
	}     
}

$nombre_impresora = "POSMELI";
$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);
$printer->setJustification(Printer::JUSTIFY_CENTER);

//detalles de la factura como iva/ subtotal y total
$subtotal = new item('Subtotal',"$". number_format($subtotal1));
$impo = new item('Impoconsumo', "$".number_format($impoconsumo));
$tax = new item('IVA',"$". number_format($total_iva));
$totales = new item('Total');
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
$printer -> text("NIT :".$nit_empresa."\n");
$printer -> feed(2);
$printer -> selectPrintMode();
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> text("DIRECCION :\n");
$printer -> text($direccion_empresa."\n");
$printer -> text("TELEFONO :".$telefono_empresa."\n");
$printer -> text("FACT- # :".$prefijo."-" .$id."\n");
$printer -> feed();
//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
$printer -> text("--------------------------------");
$printer -> text("Forma de Pago : Efectivo\n");
$printer -> text("Nit/ Cc : ".$identificacion."\n");
$printer -> text("Cliente : ".$cliente."\n");
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
$printer -> text(" Producto");
$printer -> text(" |Vlr");
$printer -> text(" |Cant");
$printer -> text(" |Total");

/* Items para darle un espacio*/
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);

/* DATOS TRAIDOS DE LA CONSULTA SQL*/
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
$printer -> text("$".number_format($totalt));
$printer -> selectPrintMode();

//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
$printer -> feed(2);
$printer -> text("--------------------------------");
$printer -> text("Valor Recibido : ".number_format($pago)."\n");
$printer -> text("Cambio : ".number_format($devolucion)."\n");
$printer -> text("--------------------------------");
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
header("Location: ../index.php");*/

?>