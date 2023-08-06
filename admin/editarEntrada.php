<?php 
if (isset($_POST['Editentrada'])) {
	$id_stock = $sqlconnection->real_escape_string($_POST['id_stock']);
	//$itemID_fk = $sqlconnection->real_escape_string($_POST['itemID_fk']);
	$fecha_stock = $sqlconnection->real_escape_string($_POST['fecha_stock']);
	$cantidad_stock_anterior = $sqlconnection->real_escape_string($_POST['cantidad_stock_anterior']);
	$cantidad_stock_form = $sqlconnection->real_escape_string($_POST['cantidad_stock']);
	$precio_compra = $sqlconnection->real_escape_string($_POST['precio_compra']);
	$precio_venta = $sqlconnection->real_escape_string($_POST['precio_venta']);
	$vendedor = $_SESSION['username'];

	$QueryItems =  "
	SELECT *
	FROM entrada_stock WHERE id_stock='{$id_stock}'";
	if ($orderResultItem1 = $sqlconnection->query($QueryItems)) {
		if ($orderResultItem1->num_rows == 0) {
			echo "ERROR";
		}else {
			while($filaP = $orderResultItem1->fetch_array(MYSQLI_ASSOC)) {
				$cantidad_stock=$filaP['cantidad_stock'];
				$itemID_fk=$filaP['itemID_fk'];
				echo $cantidad_stock;
			}
			$QueryItems2 =  "
			SELECT *
			FROM tbl_menuitem WHERE itemID='{$itemID_fk}'";
			if ($orderResultItem = $sqlconnection->query($QueryItems2)) {
				if ($orderResultItem->num_rows == 0) {
					echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
					
				}else {
					while($filaP2 = $orderResultItem->fetch_array(MYSQLI_ASSOC)) {
						$stock=$filaP2['stock'];
						echo $stock;
					}
					$nuevoStock1=($stock-$cantidad_stock_anterior);
					$nuevoStock=$nuevoStock1+$cantidad_stock_form;
					echo $nuevoStock;
					$updateItemQuery = "UPDATE tbl_menuitem SET stock = '{$nuevoStock}',precio_costo = '{$precio_compra}',price = '{$precio_venta}'  WHERE itemID = '{$itemID_fk}' ";

					if ($sqlconnection->query($updateItemQuery) === TRUE) {
						$updateStock = "UPDATE entrada_stock SET cantidad_stock = '{$cantidad_stock_form}',precio_compra = '{$precio_compra}',precio_venta = '{$precio_venta}',vendedor = '{$vendedor}'  WHERE id_stock = '{$id_stock}' ";

						if ($sqlconnection->query($updateStock) === TRUE) {
							echo '<script>
							swal("Buen Trabajo!", "Se edito con éxito", "success").then(function() {
								window.location.replace("entradas_stock.php");
								});

								</script>';
								exit();
							}else{
								echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error ", "error");</script>';
							}
						}else{
							echo "ERROR AL EDITAR EL STOCK";
						}

					}
				}
			}

		}
	}
?>