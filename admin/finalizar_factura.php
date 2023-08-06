<?php
include("../functions.php");	


if (isset($_POST['pagar'])) {

	$total_propina=$_POST['total_propina'];
	$orderIDMesa=$_POST['orderIDMesa'];
	$items1 = ($_POST['pago']);
	$items2 = ($_POST['orderID']);
	$items3 = ($_POST['id_caja_fk']);
	$items4 = ($_POST['monto_recibido']);
	

	while(true) {

            //// RECUPERAR LOS VALORES DE LOS ARREGLOS ////////
		$item1 = current($items1);
		$item2 = current($items2);
		$item3 = current($items3);
		$item4 = current($items4);
		$item5 = ($item5 = $item4-$item1);
		


            ////// ASIGNARLOS A VARIABLES ///////////////////
		$pago= (($item1 !== false) ? $item1 : ", &nbsp;");
		$orderID= $item2;
		$id_caja_fk= (($item3 !== false) ? $item3 : ", &nbsp;");
		$monto_recibido= (($item4 !== false) ? $item4 : ", &nbsp;");
		$devolucion= $item5;
            //// CONCATENAR LOS VALORES EN ORDEN PARA SU FUTURA INSERCIÓN ////////
		$valores='("'.$orderID.'","'.$id_caja_fk.'","'.$monto_recibido.'","'.$pago.'","'.$devolucion.'"),';

            //////// YA QUE TERMINA CON COMA CADA FILA, SE RESTA CON LA FUNCIÓN SUBSTR EN LA ULTIMA FILA /////////////////////
		$valoresQ= substr($valores, 0, -1);

            ///////// QUERY DE INSERCIÓN ////////////////////////////
		$sql = "INSERT INTO pagos (orderID, id_caja_fk,monto_recibido,pago_orden,devuelto) 
		VALUES $valoresQ";

		$sqlconnection->query($sql);
        // Up! Next Value
		$item1 = next( $items1 );

		$item3 = next( $items3 );
		$item4 = next( $items4 );

            // Check terminator
		if($item1 === false && $item3 === false && $item4 === false ) break;

	}

	$todo =  "
	SELECT *
	FROM tbl_order 
	WHERE orderID='{$orderIDMesa}'";
	if ($orderResult = $sqlconnection->query($todo)) {
                        //if no order
		if ($orderResult->num_rows == 0) {

			echo "ERROR";
		}

		else {
			while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
				$id=$orderRow['orderID'];
				$total=$orderRow['total'];
				$id_mesa=$orderRow['id_mesa_fk'];
			}
			$nuevo_estado="Habilitada";
			$editmesa = "UPDATE mesa SET estado_mesa = '{$nuevo_estado}' WHERE id_mesa = '{$id_mesa}'";  

			if ($sqlconnection->query($editmesa) === TRUE) {
				echo "inserted.";
			} 

			else {
				//handle
				echo "someting wong";
				echo $sqlconnection->error;

			}
			
		}


	}
	$pagosacum =  "
	SELECT *
	FROM pagos 
	WHERE orderID='{$orderIDMesa}'";
	if ($orderResultPago = $sqlconnection->query($pagosacum)) {
                        //if no order
		if ($orderResultPago->num_rows == 0) {

			echo "ERROR";
		}

		else {
			$acumPago=0;
			while($orderpago = $orderResultPago->fetch_array(MYSQLI_ASSOC)) {
				$pago_orden=$orderpago['pago_orden'];
				$acumPago+=$pago_orden;

			}
			
			
		}


	}
	
	//CONDICION  PARA SABER SI ESTA PAGA O A CREDITO
	if ($total>$acumPago) {
		$nuevo_estado_O="Credito";
		
	}else{

		$nuevo_estado_O="Vendido";
	}
	
	$editmesa = "UPDATE tbl_order SET status = '{$nuevo_estado_O}' WHERE orderID = '{$orderIDMesa}'";  

	if ($sqlconnection->query($editmesa) === TRUE) {
		echo "inserted.";
		echo $debe;
	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}


	$Detalles = "SELECT * FROM tbl_orderdetail WHERE orderID='{$orderIDMesa}'";

	if ($menuResult = $sqlconnection->query($Detalles)) {
		
		while($detallesItem = $menuResult->fetch_array(MYSQLI_ASSOC)) { 
			$itemID=$detallesItem['itemID'];
			$quantity=$detallesItem['quantity'];
			
			$tblItem = "SELECT * FROM tbl_menuitem WHERE itemID='{$itemID}'";

			if ($menuResult2 = $sqlconnection->query($tblItem)) {

				while($menuRow = $menuResult2->fetch_array(MYSQLI_ASSOC)) { 
					$precio=$menuRow['price'];
					$stock=$menuRow['stock'];
					$iva=$menuRow['iva'];
					$itemID2=$menuRow['itemID'];
					$nuevoStock=$stock-$quantity;
					/*=======================================
					EDITAR EL STOCK DE LOS PRODUCTOS
					=======================================*/
					$editStock = "UPDATE tbl_menuitem SET stock = '{$nuevoStock}' WHERE itemID = '{$itemID2}'";  

					if ($sqlconnection->query($editStock) === TRUE) {
						echo "inserted";
						
					} 

					else {
				//handle
						echo "someting wong";
						echo $sqlconnection->error;

					}
				}
			}
		}
	}

	/*=======================================
	GUARDAR LA PROPINA
	=======================================*/
	$addPropina = "INSERT INTO propinas (valor_propina,cod_venta) VALUES ('{$total_propina}','{$orderIDMesa}') ";

	if ($sqlconnection->query($addPropina) === TRUE) {
		header("Location: impresion-edit.php?orderID=$orderIDMesa");
		exit();

	}else {
			//handle
		echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
		echo $sqlconnection->error;
	}
}

?>