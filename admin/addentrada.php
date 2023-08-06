<?php

	//Add new menu (category)
if (isset($_POST['addentrada'])) {

	if (!empty($_POST['cantidad_stock'])) {
		$fecha_stock = $sqlconnection->real_escape_string($_POST['fecha_stock']);
		$cantidad_stock = $sqlconnection->real_escape_string($_POST['cantidad_stock']);
		$precio_compra = $sqlconnection->real_escape_string($_POST['precio_compra']);
		$precio_venta = $sqlconnection->real_escape_string($_POST['precio_venta']);
		$itemID_fk = $sqlconnection->real_escape_string($_POST['itemID_fk']);
		$vendedor = $sqlconnection->real_escape_string($_POST['vendedor']);
		$addMenuQuery = "INSERT INTO entrada_stock (fecha_stock,cantidad_stock, precio_compra,precio_venta,itemID_fk,vendedor) VALUES ('{$fecha_stock}','{$cantidad_stock}','{$precio_compra}','{$precio_venta}','{$itemID_fk}','{$vendedor}')";

		if ($sqlconnection->query($addMenuQuery) === TRUE) {
			$QueryItems =  "
			SELECT *
			FROM tbl_menuitem WHERE itemID='{$itemID_fk}'";
			if ($orderResultItem = $sqlconnection->query($QueryItems)) {
				if ($orderResultItem->num_rows == 0) {
					echo "ERROR";
				}else {
					while($filaP = $orderResultItem->fetch_array(MYSQLI_ASSOC)) {
						$stock=$filaP['stock'];
						$nuevoStock=$cantidad_stock+$stock;
						$updateItemQuery = "UPDATE tbl_menuitem SET stock = '{$nuevoStock}' , price = '{$precio_venta}' , precio_costo='{$precio_compra}' WHERE itemID = '{$itemID_fk}'";

						if ($sqlconnection->query($updateItemQuery) === TRUE) {
							echo '
							<script>
							swal("Buen Trabajo!", "Se edito con éxito", "success");
							</script>';
						}else {
							//handle
							echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
							echo $sqlconnection->error;
							echo $updateItemQuery;
						}
						
					}
				}
			}
		} else {
			echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
		}
	}

		//No input handle
	else {
		echo '<script>swal("ERROR!", "Lo sentimos ocurrió un Error", "error");</script>';

	}

}
?>