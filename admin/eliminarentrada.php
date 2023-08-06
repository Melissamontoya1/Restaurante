<?php

include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');

if (isset($_GET['id_entradas'])) {
	$id_stock = $sqlconnection->real_escape_string($_GET['id_entradas']);

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
					$nuevoStock=$stock-$cantidad_stock;
					echo $nuevoStock;
					$updateItemQuery = "UPDATE tbl_menuitem SET stock = '{$nuevoStock}'  WHERE itemID = '{$itemID_fk}' ";

					if ($sqlconnection->query($updateItemQuery) === TRUE) {
						$deleteMenuQuery = "DELETE FROM entrada_stock WHERE id_stock = {$id_stock}";

						if ($sqlconnection->query($deleteMenuQuery) === TRUE) {
							echo '<script>
							swal("Buen Trabajo!", "Se elimino con éxito", "success").then(function() {
								window.location.replace("entradas_stock.php");
								});

								</script>';
								exit();
							}else {
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