<?php 
/* DATOS TRAIDOS DE LA CONSULTA SQL*/

include("../functions.php");
include("empresa_datos.php");

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
		$nombre_impresora = "PIZZAH";
		$connector = new WindowsPrintConnector($nombre_impresora);
		$printer = new Printer($connector);
		

		$orden =  "
		SELECT o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo,od.estado_producto
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
		WHERE od.estado_producto='Pendiente' GROUP BY o.orderID
		";

		if ($orderResult = $sqlconnection->query($orden)) {

			$currentspan = 0;
			$total = 0;

                        //if no order
			if ($orderResult->num_rows == 0) {

				echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momentoaa. </td></tr>";
			}else {

				while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
					$cliente=$orderRow['nombres'];
					$subtotal=$orderRow['subtotal'];
					//$impoconsumo=$orderRow['impoconsumo'];
					//$total_iva=$orderRow['total_iva'];
					$prefijo=$orderRow['prefijo'];
					$observacion_order=$orderRow['observacion_order'];
					$nombre_tipo=$orderRow['nombre_tipo'];
					$numero_mesa=$orderRow['numero_mesa'];
					$estado_producto=$orderRow['estado_producto'];
					$orderIDimpre=$orderRow['orderID'];
					$nombre_area=$orderRow['nombre_area'];

					echo $estado_producto;
			//SI EL ESTADO DE LA ORDEN DETALLE ES PENDIENTE IMPRIMA LA COMANDA
					if($estado_producto=="Pendiente"){

						$printer->setJustification(Printer::JUSTIFY_CENTER);

//detalles de la factura como iva/ subtotal y total
						$subtotal = new item('Subtotal', number_format($total_factura,0));

						$totales = new item('Total');
						/* Date is kept the same for testing */
						$date = date('d/m/Y h:i:s A');



						$printer -> feed();
//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
						$printer -> text("--------------------------------");
// $printer -> text("Forma de Pago : Efectivo\n");
						$printer -> text("Tipo Mesa : ".$nombre_tipo."\n");
						$printer -> text("Numero Mesa : ".$numero_mesa."\n");
						$printer -> text("Area  : ".$nombre_area."\n");
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
						// $printer -> text(" Producto");
						// $printer -> text(" |UND");
						// $printer -> text(" |Cant");
						// $printer -> text(" |Total");
						/* Items para darle un espacio*/
						/* Items para darle un espacio*/
						$printer -> setJustification(Printer::JUSTIFY_LEFT);
						$printer -> setEmphasis(true);
						$printer -> text(new item(''));
						$printer -> setEmphasis(false);

						/* DATOS TRAIDOS DE LA CONSULTA SQL*/
						$id_orden= $orderIDimpre;
						$displayOrderQuery =  "
						SELECT o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
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

						if ($orderResultp = $sqlconnection->query($displayOrderQuery)) {
                        //if no order
							if ($orderResultp->num_rows == 0) {

								echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
							}

							else {
								$entregado="Entregado";
								$cambiar_estado = "UPDATE tbl_orderdetail SET estado_producto = '{$entregado}' WHERE orderID = '{$orderIDimpre}'";  

								if ($sqlconnection->query($cambiar_estado) === TRUE) {
									echo "inserted.";

								} 

								else {
				//handle
									echo "someting wong";
									echo $sqlconnection->error;

								}

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

									$printer->setJustification(Printer::JUSTIFY_LEFT);
									$printer->text($menu."\n");
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
						// $printer -> setEmphasis(true);
						// $printer -> text($subtotal);
						// $printer -> setEmphasis(false);
						// $printer -> feed();
						$printer -> feed(2);
						$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
						$printer -> text($totales);
						$printer -> text("$".number_format($totalt));
						$printer -> feed(2);
						$printer -> setEmphasis(true);
						$printer->setJustification(Printer::JUSTIFY_CENTER);
						//$printer -> text($nombre_empresa."\n");
						$printer -> text("OBSERVACION :".$observacion_order."\n");
						$printer -> text("--------------------------------");
						$printer -> setEmphasis(false);
						$printer -> feed(2);


						/*Corte el recibo y abra el cajón o monedero */
						$printer -> cut();
						$printer -> Pulse();
						/*Cerramos la sesion de la impresora*/
						$printer -> close();

/*REDIRECIONAMOS AL INDEX
header("Location: ../index.php");*/


}

}
}     
}     


?>