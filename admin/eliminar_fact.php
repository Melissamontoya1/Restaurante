<?php 
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');



$orderID = $sqlconnection->real_escape_string($_GET['orderID']);
$todo =  "
SELECT *
FROM tbl_order 
WHERE orderID='{$orderID}'";
if ($orderResult = $sqlconnection->query($todo)) {
                        //if no order
	if ($orderResult->num_rows == 0) {

		echo "ERROR";
	}

	else {
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$id=$orderRow['orderID'];
			$total=$orderRow['total'];
			$pago=$orderRow['pago'];
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

$menuQuery = "SELECT * FROM tbl_orderdetail WHERE orderID='{$orderID}'";

if ($menuResult = $sqlconnection->query($menuQuery)) {

	while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) { 
		$itemID=$menuRow['itemID'];
		$devolver_stock=$menuRow['quantity'];
		$detalle = "SELECT * FROM detalle_preparacion WHERE itemID_fk='{$itemID}'";

		if ($menuResult1 = $sqlconnection->query($detalle)) {
			$counter = 0;
			while($menuRow2 = $menuResult1->fetch_array(MYSQLI_ASSOC)) { 
				$id_preparacion=$menuRow2['id_preparacion_fk'];
				$cantidad_detalle=$menuRow2['cantidad_detalle'];

			}
		}
		$cantidad_total=$cantidad_detalle*$devolver_stock;
		$menuQuery2 = "SELECT * FROM stock_preparacion WHERE id_preparacion='{$id_preparacion}'";

		if ($menuResult2 = $sqlconnection->query($menuQuery2)) {
			$counter = 0;
			while($menuRow3 = $menuResult2->fetch_array(MYSQLI_ASSOC)) { 
				$stockP=$menuRow3['stock_preparacion'];


			}
		}
		$nuevoStockP=$stockP+$cantidad_total;
		$editStock = "UPDATE stock_preparacion SET stock_preparacion = '{$nuevoStockP}' WHERE id_preparacion = '{$id_preparacion}'";  

		if ($sqlconnection->query($editStock) === TRUE) {
			echo "inserted.";
		} 

		else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;

		}


	
	$menuQuery22 = "SELECT * FROM tbl_menuitem WHERE itemID='{$itemID}'";

	if ($menuResult22 = $sqlconnection->query($menuQuery22)) {

		while($menuRow4 = $menuResult22->fetch_array(MYSQLI_ASSOC)) { 

			$stock=$menuRow4['stock'];



			$nuevoStock=$devolver_stock+$stock;
			$editStock = "UPDATE tbl_menuitem SET stock = '{$nuevoStock}' WHERE itemID = '{$itemID}'";  

			if ($sqlconnection->query($editStock) === TRUE) {
				echo "inserted.";
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

$eliminarDetalle = "DELETE FROM tbl_orderdetail WHERE orderID = {$orderID}";

if ($sqlconnection->query($eliminarDetalle) === TRUE) {
	$EliminarOrden = "DELETE FROM tbl_order WHERE orderID = {$orderID}";

	if ($sqlconnection->query($EliminarOrden) === TRUE) {
			$EliminarPropina = "DELETE FROM propinas WHERE cod_venta = {$orderID}";

		if ($sqlconnection->query($EliminarPropina) === TRUE) {
			echo "propina eliminada";
		} else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;
		}
		$EliminarPago = "DELETE FROM pagos WHERE orderID = {$orderID}";

		if ($sqlconnection->query($EliminarPago) === TRUE) {
			echo '<script>
			swal("Buen Trabajo!", "Se elimino con éxito", "success").then(function() {
				window.location.replace("sales.php");
				});

				</script>';
		} else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;
		}
	} else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;
	}
}else {
				//handle
	echo "someting wong";
	echo $sqlconnection->error;
}




?>