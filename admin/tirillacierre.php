<?php
include("../functions.php");
$id_base= $_GET['id_base'];

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
			$nombre_impresora=$fila2['nombre_impresora'];
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
$consultarOrden =  "
SELECT *
FROM base
WHERE id_base='{$id_base}'";

if ($orderResult = $sqlconnection->query($consultarOrden)) {
	//if no order
	if ($orderResult->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. 1 </td></tr>";
	}

	else {
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$id_base=$orderRow['id_base'];
			$fecha_inicio=$orderRow['fecha_inicio'];
			$fecha_fin=$orderRow['fecha_fin'];
			$valor_base=$orderRow['valor_base'];
			$ventas_cierre=$orderRow['ventas_cierre'];
			$gastos_cierre=$orderRow['gastos_cierre'];
			$utilidad_cierre=$orderRow['utilidad_cierre'];
		}
	}     
}


$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);
$printer->setJustification(Printer::JUSTIFY_CENTER);

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
$printer -> text("----------------\n");
$printer -> text("CIERRE DE CAJA\n");
$printer -> selectPrintMode();
//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
$printer -> text("--------------------------------"."\n");
$printer -> text("Apertura : ".$fecha_inicio."\n");
$printer -> text("Cierre   : ".$fecha_fin."\n");
$printer -> text("--------------------------------"."\n");
/* Titulo del Recibo */
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("DETALLES DE VENTAS - ".$id_base."\n");
$printer -> setEmphasis(false);
/* Items para darle un espacio */
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);
/* FILAS*/
$printer -> text(" # Factura      ");
$printer -> text("    |Total");

/* Items para darle un espacio*/
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);

/* DATOS TRAIDOS DE LA CONSULTA SQL*/
//$fechaTirilla= $_GET['fechaTirilla'];
$displayOrderQuery =  "
SELECT *
FROM pagos 
WHERE  id_cierre='".$id_base."' ";

if ($orderResult = $sqlconnection->query($displayOrderQuery)) {

	if ($orderResult->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. 2</td></tr>";
	}

	else {
		
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$orderID=$orderRow['orderID'];
			$fecha=$orderRow['fecha_pago'];
			$totalt=$orderRow['pago_orden'];
			if ($totalt>0) {
				
			/*Alinear a la izquierda para la cantidad y el nombre*/
			$printer->setJustification(Printer::JUSTIFY_LEFT);
			$printer->text("   #".$orderID."                $ ". number_format($totalt)."\n" );


			}
		}
	}     
}
$printer -> feed(2);
$printer -> text("================================"."\n");

$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("PROPINAS - ".$fecha_inicio."\n");
$printer -> setEmphasis(false);
$printer -> text("================================"."\n");

/* Items para darle un espacio */
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);
/* FILAS*/

$printer -> text(" # Factura      ");
$printer -> text("    | $ Propina");

/* Items para darle un espacio*/
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);
	//CONSULTAR TOTAL EN LAS CUENTAS 
$Propinas2 =  "
SELECT *
FROM propinas WHERE id_base_fk = '{$id_base}' ORDER BY id_propina DESC";
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
			/*Alinear a la izquierda para la cantidad y el nombre*/
			if($valor_propina>0){
				/*Alinear a la izquierda para la cantidad y el nombre*/
			$printer->setJustification(Printer::JUSTIFY_LEFT);
			$printer->text("   #".$cod_venta."                $ ". number_format($valor_propina)."\n" );

			}

		}
	}     
}
$printer -> feed(2);
$printer -> text("================================"."\n");

// GASTOS DEL DIA
/* Titulo del Recibo */
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("DETALLES DE GASTOS - ".$fecha_inicio."\n");
$printer -> setEmphasis(false);
/* Items para darle un espacio */
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);
/* FILAS*/

$printer -> text("Descripcion   |   ");
$printer -> text("Valor Gastos");

/* Items para darle un espacio*/
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);

/* DATOS TRAIDOS DE LA CONSULTA SQL*/
$displayOrderQuery =  "
SELECT *
FROM gastos 
WHERE id_base_fk='".$id_base."'";

if ($orderResult = $sqlconnection->query($displayOrderQuery)) {

	if ($orderResult->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento.3 </td></tr>";
	}

	else {
		while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$id_gasto=$fila['id_gasto'];
			$fecha_gasto=$fila['fecha_gasto']; 
			$descripcion_gasto=$fila['descripcion_gasto'];
			$valor_gasto=$fila['valor_gasto']; 

			/*Alinear a la izquierda para la cantidad y el nombre*/
			$printer->setJustification(Printer::JUSTIFY_LEFT);
			$printer->text($descripcion_gasto."     $".number_format($valor_gasto). "\n" );	
		}
	}     
}


//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
$printer -> feed(2);
$printer -> text("================================"."\n");

//$fechaTirilla= $_GET['fechaTirilla'];
$displayOrderQuery =  "
SELECT c.id_caja, c.nombre_caja, c.estado_caja,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, SUM(p.pago_orden) AS totalc, p.devuelto, p.fecha_pago
	FROM pagos p
	INNER JOIN tipo_caja c
	ON p.id_caja_fk=c.id_caja
	WHERE p.fecha_pago='{$id_base}'
	GROUP BY p.id_caja_fk
	ORDER BY p.id_caja_fk  ASC ";

if ($orderResult = $sqlconnection->query($displayOrderQuery)) {

	if ($orderResult->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. 4</td></tr>";
	}

	else {
		while($orderpago = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$orderID=$orderpago['orderID'];
				$pago_orden=$orderpago['totalc'];
				$nombre_caja=$orderpago['nombre_caja'];
				$devuelto=$orderpago['devuelto'];
				$monto_recibido=$orderpago['monto_recibido'];
				$acumPago+=$pago_orden;

			/*Alinear a la izquierda para la cantidad y el nombre*/
			$printer -> text($nombre_caja.":  $".number_format($pago_orden)."\n");	
		}
	}     
}
$caja =  "
	SELECT p.id_pago,SUM(p.pago_orden) AS totale,p.fecha_pago,p.id_caja_fk,p.id_cierre,t.id_caja,t.nombre_caja
FROM pagos p
INNER JOIN tipo_caja t
ON p.id_caja_fk = t.id_caja
WHERE p.id_cierre='$id_base' AND t.nombre_caja='Efectivo'
GROUP BY p.id_caja_fk
ORDER BY p.id_caja_fk  ASC ";

if ($resultcaja = $sqlconnection->query($caja)) {

	if ($resultcaja->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. 5</td></tr>";
	}

	else {
		while($fila2 = $resultcaja->fetch_array(MYSQLI_ASSOC)) {
			$nombre_efectivo=$fil2a['nombre_caja'];
			$total_efectivo=$fila2['totale'];  
		}
	}     
}

$total_final=($total_efectivo-$gastos_cierre);
//$printer -> text("Total General:  $".number_format($subtotal)."\n");
//$printer -> text("Descuentos:  $".number_format($descuento)."\n");
$printer -> text("Total Ventas:  $".number_format($ventas_cierre)."\n");
/* Titulo del Recibo */
$printer -> text("================================"."\n");
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("EFECTIVO EN CAJA \n");
$printer -> setEmphasis(false);
$printer -> text("Valor Base :   $".number_format($valor_base)."\n");
$printer -> text("Ventas en Efectivo: $".number_format($total_efectivo)."\n");
$printer -> text("BASE + VENTAS:  $".number_format($valor_base+$total_efectivo)."\n");
$printer -> text("Total Gastos Efectivo:  $".number_format($gastos_cierre)."\n");
$printer -> text("Total Efectivo :  $".number_format($valor_base+$total_final)."\n");
$printer -> text("================================"."\n");
$printer -> text("Propinas :  $".number_format($acumPropina)."\n");

/* Titulo del Recibo */
$printer -> text("================================");
/* Titulo del Recibo */
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("DETALLES DE PRODUCTOS\n");
$printer -> setEmphasis(false);

/* FILAS*/
$printer -> text(" Producto");
$printer -> text(" |Cant");
$printer -> text(" |Total\n");
$printer -> text("================================");

/* Items para darle un espacio*/
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item(''));
$printer -> setEmphasis(false);

	$productosD =  "
	SELECT o.orderID,o.prefijo,o.total,o.subtotal,o.total_desc, m.menuName, od.itemID,mi.menuItemName,SUM(od.quantity) AS cantidadp,od.precio,SUM(od.totalv) AS totalvp,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, p.pago_orden, p.devuelto, p.fecha_pago, p.id_cierre
FROM tbl_order o
INNER JOIN tbl_orderdetail od
ON o.orderID = od.orderID
INNER JOIN tbl_menuitem mi
ON od.itemID = mi.itemID
INNER JOIN tbl_menu m
ON mi.menuID = m.menuID
INNER JOIN pagos p 
ON p.orderID= o.orderID
WHERE p.id_cierre='{$id_base}'
GROUP BY mi.menuItemName ";
	if ($orderResultPro = $sqlconnection->query($productosD)) {
                        //if no order
		if ($orderResultPro->num_rows == 0) {

			echo "ERROR";
		}else {
		
			while($orderPro = $orderResultPro->fetch_array(MYSQLI_ASSOC)) {
				$nombreP=$orderPro['menuItemName'];
				    $cantidadP=$orderPro['cantidadp'];
				    $totalvpr=$orderPro['totalvp'];

				/*Alinear a la izquierda para la cantidad y el nombre*/
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($nombreP."\n ".$cantidadP." -  $". number_format($totalvpr)."\n\n" );

			}
		}     
	}

/* Footer - Pie de pagina */
$printer -> feed(2);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Gracias por preferirnos\n");
$printer -> text($resolucion);
//$printer -> feed(2);


/*Corte el recibo y abra el cajón o monedero */
$printer -> cut();
$printer -> Pulse();
/*Cerramos la sesion de la impresora*/
$printer -> close();

/*REDIRECIONAMOS AL INDEX
header("Location: ../index.php");*/

/* Un contenedor para organizar los nombres y precios de artículos en columnas */

//ENVIAR CORREO DESDE LOCALHOST SIN LIBRERIA

// $para      = 'vstiven@hotmail.com,mayrasuaza@hotmail.com';
// $asunto    = 'CIERRE DEL DIA EL PUERTO TERRAZA '.$fechaTirilla;
// $descripcion   = 'Hola, a continuacion encontraras el detalle general de las ventas, gastos,base del dia y el monto total que hay en caja'."\n".
// '--------------------------------------------------'."\n".
// 'Valor Base :     $'.number_format($valor_base)."\n".
// 'Subtotal :        $'.number_format($subtotal)."\n".
// 'Descuentos:    $'.number_format($descuento)."\n".
// 'Total Ventas:  $'.number_format($ventas_cierre)."\n".
// 'Total Gastos:  $'.number_format($gastos_cierre)."\n".
// 'Total en Caja: $'.number_format($utilidad_cierre)."\n"; 

// $de = 'From: elpuertot@gmail.com';

// if (mail($para, $asunto, $descripcion, $de))
// {
// 	echo "Correo enviado satisfactoriamente";
// }
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'mail/autoload.php';

$mail = new PHPMailer(true);


$mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->isSMTP();
$mail->Host = 'smtp.hostinger.com';
$mail->SMTPAuth = true;
$mail->Username = 'info@aylriesgosyseguros.com';
$mail->Password = 'Montoya@9804';
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;
$mail->CharSet = 'UTF-8';


$mail->setFrom('info@aylriesgosyseguros.com', 'CIERRE DEL DIA');

$correosin = "SELECT * FROM correos WHERE id_empresa_fk='{$id_empresa}' ";

if ($result = $sqlconnection->query($correosin)) {

	if ($result->num_rows > 0) {

		while($rempresa = $result->fetch_array(MYSQLI_ASSOC)) {
			$correo_empresa=$rempresa['correo_empresa'];
			$mail->addAddress($correo_empresa);
		}
	}
	
    //$mail->addCC('yumemogu@gmail.com');
	if ($archivo_correo=="") {

	}else{
		$mail->addAttachment('archivos_correo/'.$archivo_correo, $archivo_correo);
	}
	$date = date('d/m/Y h:i:s A');
	$titulo_correo="CIERRE DEL DIA  - $date";
	$message  = "<html><body>";

	$message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";

	$message .= "<tr><td>";

	$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

	$message .= "<thead>
	<tr height='80'>
	<th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:34px;' >Informe Del Dia</th>
	</tr>
	</thead>";

	$message .= "<tbody>
	<tr>
	<td colspan='4' style='padding:15px;'>
	<p style='font-size:20px;color:black;'>Hola <B>".$nombre_empresa."</B>, a continuacion encontraras el detalle general de las ventas, gastos,base del dia y el monto total que hay en caja.  </p>

	</td>
	</tr>
	<tr height='80'>
	<th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:24px;' >Pagos Recibidos</th>
	</tr>

	
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#00a2d1; text-align:center;'># Factura </td>
	<td style='background-color:#00a2d1; text-align:center;'>Metodo Pago </td>
	<td style='background-color:#00a2d1; text-align:center;'>Total</td>

	</tr>
	";
	$pagosacum =  "
	SELECT c.id_caja, c.nombre_caja, c.estado_caja,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, p.pago_orden, p.devuelto, p.fecha_pago,p.id_cierre
	FROM pagos p
	INNER JOIN tipo_caja c
	ON p.id_caja_fk=c.id_caja
	WHERE p.id_cierre='{$id_base}'";
	if ($orderResultPago = $sqlconnection->query($pagosacum)) {
                        //if no order
		if ($orderResultPago->num_rows == 0) {

			echo "ERROR";
		}else {
			$acumPago=0;
			while($orderpago = $orderResultPago->fetch_array(MYSQLI_ASSOC)) {
				$orderID=$orderpago['orderID'];
				$pago_orden=$orderpago['pago_orden'];
				$nombre_caja=$orderpago['nombre_caja'];
				$devuelto=$orderpago['devuelto'];
				$monto_recibido=$orderpago['monto_recibido'];
				$acumPago+=$pago_orden;


				
				$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'>
				<td>".$orderID."</td>
				<td>".$nombre_caja."</td>
				<td>$ ".number_format($pago_orden)."</td></tr>";

			}
		}     
	}
	$message .= "
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#00a2d1; text-align:center;'>Total Ventas</td>
	<td style='background-color:#00a2d1; text-align:center;'></td>
	<td style='background-color:#00a2d1; text-align:center;'><B>$ ".number_format($acumPago)."</B></td>

	</tr> ";

	$message .= "</tbody>";

	$message .= "</table>";

	//GASTOS DEL DIA CORREO
	$message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";

	$message .= "<tr><td>";

	$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

	$message .= "<thead>
	<tr height='80'>
	<th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:24px;' >Gastos</th>
	</tr>
	</thead>";

	$message .= "<tbody>
	
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#E74C3C; text-align:center;'>Descripción </td>

	<td style='background-color:#E74C3C; text-align:center;'>Valor Gasto</td>

	</tr>
	";
	$gastoscorreo =  "
	SELECT *
	FROM gastos 
	WHERE id_base_fk='".$id_base."'";

	if ($orderResult = $sqlconnection->query($gastoscorreo)) {

		if ($orderResult->num_rows == 0) {

			$message .= "<tr><td class='text-center' colspan='7' >No se registraron gastos </td></tr>";
		}

		else {
			while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {
				$id_gasto=$fila['id_gasto'];
				$fecha_gasto=$fila['fecha_gasto']; 
				$descripcion_gasto=$fila['descripcion_gasto'];
				$valor_gasto=$fila['valor_gasto']; 

				$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'><td>".$descripcion_gasto."</td>
				<td>$ ".number_format($valor_gasto)."</td></tr>";

			}
		}     
	}

	$message .= "
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#E74C3C; text-align:center;'>Total Gastos</td>

	<td style='background-color:#E74C3C; text-align:center;'><B>$ ".number_format($gastos_cierre)."</B></td>

	</tr> ";


	$message .= "</tbody>";

	$message .= "</table>";

	//CUENTAS DEL DIA CORREO
	$message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";

	$message .= "<tr><td>";

	$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

	$message .= "<thead>
	<tr height='80'>
	<th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:24px;' >Estado de Cuentas</th>
	</tr>
	</thead>";

	$message .= "<tbody>
	
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#F4D03F; text-align:center;'>Cuenta </td>

	<td style='background-color:#F4D03F; text-align:center;'>Ingresos</td>

	</tr>
	";
	//CONSULTAR TOTAL EN LAS CUENTAS 
	$cuentascorreo = "
	SELECT c.id_caja, c.nombre_caja, c.estado_caja,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, SUM(p.pago_orden) AS totalc, p.devuelto, p.fecha_pago,p.id_cierre
	FROM pagos p
	INNER JOIN tipo_caja c
	ON p.id_caja_fk=c.id_caja
	WHERE p.id_cierre='{$id_base}'
	GROUP BY p.id_caja_fk
	ORDER BY p.id_caja_fk  ASC ";

	if ($orderResult = $sqlconnection->query($cuentascorreo)) {

		if ($orderResult->num_rows == 0) {

			$message .= "<tr><td class='text-center' colspan='2' >No existen registros en las cuentas </td></tr>";
		}else {
			while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {
				$nombre_caja=$fila['nombre_caja'];
				$totalc=$fila['totalc']; 
				$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'><td>".$nombre_caja."</td>
				<td>$ ".number_format($totalc)."</td></tr>";

			}
		}     
	}
	$message .= "
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#F4D03F; text-align:center;'></td>

	<td style='background-color:#F4D03F; text-align:center;'></td>

	</tr> ";

	$message .= "</tbody>";

	$message .= "</table>";
	//PROPINAS
	$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

	$message .= "<thead>
	<tr height='80'>
	<th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:24px;' >Propinas</th>
	</tr>
	</thead>";

	$message .= "<tbody>
	
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#F4D03F; text-align:center;'>FACT </td>

	<td style='background-color:#F4D03F; text-align:center;'>Valor Propina</td>

	</tr>
	";
	//CONSULTAR TOTAL EN LAS CUENTAS 
	$Propinas2 =  "
              SELECT *
              FROM propinas WHERE id_base_fk = '{$id_base}' ORDER BY id_propina DESC";
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
				$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'><td>".$cod_venta."</td>
				<td>$ ".number_format($valor_propina)."</td></tr>";

			}
		}     
	}
	$message .= "
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#F4D03F; text-align:center;'>Total</td>

	<td style='background-color:#F4D03F; text-align:center;'>$".number_format($acumPropina)."</td>

	</tr> ";

	$message .= "</tbody>";

	$message .= "</table>";
	
	//GASTOS DEL DIA CORREO
	$message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";

	$message .= "<tr><td>";

	$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

	$message .= "<thead>
	<tr height='80'>
	<th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:24px;' >Detalles Del Cierre</th>
	</tr>
	</thead>";

	$message .= "<tbody>
	
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#52BE80; text-align:center;'>Detalle </td>

	<td style='background-color:#52BE80; text-align:center;'>Total</td>

	</tr>
	";
	$cuentasefectivo =  "
	SELECT c.id_caja, c.nombre_caja, c.estado_caja,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, SUM(p.pago_orden) AS totalefe, p.devuelto, p.fecha_pago,p.id_cierre
	FROM pagos p
	INNER JOIN tipo_caja c
	ON p.id_caja_fk=c.id_caja
	WHERE c.nombre_caja='Efectivo' AND p.id_cierre='{$id_base}'
	GROUP BY p.id_caja_fk
	ORDER BY p.id_caja_fk  ASC 
	 

	";

	if ($orderResult = $sqlconnection->query($cuentasefectivo)) {

		if ($orderResult->num_rows == 0) {

			echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
		}else {
			while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {
				$nombre_caja=$fila['nombre_caja'];
				$totalefe=$fila['totalefe']; 

			}
		}     
	}
	
	$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'><td>Valor Base</td>
	<td>$ ".number_format($valor_base)."</td></tr>";
	$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'><td>Subtotal</td>
	<td>$ ".number_format($subtotal)."</td></tr>";
	$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'><td>Descuentos</td>
	<td>$ ".number_format($descuento)."</td></tr>";
	$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'><td>Total Ventas</td>
	<td>$ ".number_format($ventas_cierre)."</td></tr>";
	$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'><td>Total Gastos</td>
	<td>$ ".number_format($gastos_cierre)."</td></tr>";
	$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'><td>Total Efectivo sin Gastos</td>
	<td>$ ".number_format($totalefe-$gastos_cierre)."</td></tr>";
	
		//CONSULTAR TOTAL EN LAS CUENTAS 
	$sumtotal =  "
	SELECT c.id_caja, c.nombre_caja, c.estado_caja,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, SUM(p.pago_orden) AS totalc, p.devuelto, p.fecha_pago,p.id_cierre
	FROM pagos p
	INNER JOIN tipo_caja c
	ON p.id_caja_fk=c.id_caja
	WHERE p.id_cierre='{$id_base}'
	GROUP BY p.id_caja_fk
	ORDER BY p.id_caja_fk  ASC ";

	if ($orderResult = $sqlconnection->query($sumtotal)) {

		if ($orderResult->num_rows == 0) {

			$message .= "<tr><td class='text-center' colspan='2' >No existen cuentas </td></tr>";
		}else {
			while($fila2 = $orderResult->fetch_array(MYSQLI_ASSOC)) {
				$nombre_caja=$fila2['nombre_caja'];
				$totalc=$fila2['totalc']; 
				$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'><td>".$nombre_caja."</td>
				<td>$ ".number_format($totalc)."</td></tr>";

			}
		}     
	}
	
	$message .= "
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#52BE80; text-align:center;'></td>

	<td style='background-color:#52BE80; text-align:center;'></td>

	</tr> ";

	$message .= "</tbody>";

	$message .= "</table>";

	$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

	$message .= "<thead>
	<tr height='80'>
	<th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:34px;' >Detalle de Productos</th>
	</tr>
	</thead>";

	$message .= "<tbody>
	<tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:20px;color:white;'>
	<td style='background-color:#00a2d1; text-align:center;'>Producto </td>
	<td style='background-color:#00a2d1; text-align:center;'>Cantidad</td>
	<td style='background-color:#00a2d1; text-align:center;'>Total</td>

	</tr>
	";
	$productosD =  "
	SELECT o.orderID,o.prefijo,o.total,o.subtotal,o.total_desc, m.menuName, od.itemID,mi.menuItemName,SUM(od.quantity) AS cantidadp,od.precio,SUM(od.totalv) AS totalvp,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, p.pago_orden, p.devuelto, p.fecha_pago, p.id_cierre
FROM tbl_order o
INNER JOIN tbl_orderdetail od
ON o.orderID = od.orderID
INNER JOIN tbl_menuitem mi
ON od.itemID = mi.itemID
INNER JOIN tbl_menu m
ON mi.menuID = m.menuID
INNER JOIN pagos p 
ON p.orderID= o.orderID
WHERE p.id_cierre='{$id_base}'
GROUP BY mi.menuItemName ";
	if ($orderResultPro = $sqlconnection->query($productosD)) {
                        //if no order
		if ($orderResultPro->num_rows == 0) {

			echo "ERROR";
		}else {
		
			while($orderPro = $orderResultPro->fetch_array(MYSQLI_ASSOC)) {
				$nombreP=$orderPro['menuItemName'];
				    $cantidadP=$orderPro['cantidadp'];
				    $totalvpr=$orderPro['totalvp'];

				
				$message .= "<tr  align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;font-size:15px;color:black;'>
				<td>".$nombreP."</td>
				<td>".$cantidadP."</td>
				<td>$ ".number_format($totalvpr)."</td></tr>";

			}
		}     
	}


	$message .= "</tbody>";

	$message .= "</table>";

	//CIERRE FINAL 
	$message .= "</body></html>";

	$mail->isHTML(true);
	$mail->Subject =  $titulo_correo;
	$mail->Body =  $message;
	$mail->send();

	echo 'Correo enviado';
	echo "<script> 
	window.location.href='./cierref.php'; </script>";

}else{  echo $sqlconnection->error;
	echo "ERROR al enviar el correo";
}



?>
