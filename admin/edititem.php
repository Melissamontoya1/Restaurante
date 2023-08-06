<?php
	if(isset($_POST['btnedit'])) {

		if (!empty($_POST['itemName']) && !empty($_POST['itemEstado']) ) {

			$menuID = $sqlconnection->real_escape_string($_POST['menuID']);
			$itemID = $sqlconnection->real_escape_string($_POST['itemID']);
			$itemName = $sqlconnection->real_escape_string($_POST['itemName']);
			$itemPrice = $sqlconnection->real_escape_string($_POST['itemPrice']);
			$precio_costo = $sqlconnection->real_escape_string($_POST['precio_costo']);
			$stock = $sqlconnection->real_escape_string($_POST['stock']);
			$iva = $sqlconnection->real_escape_string($_POST['iva']);
			$itemEstado = $sqlconnection->real_escape_string($_POST['itemEstado']);

			$updateItemQuery = "UPDATE tbl_menuitem SET menuItemName = '{$itemName}' , price = '{$itemPrice}',stock = '{$stock}',iva = '{$iva}' , estado = '{$itemEstado}' , precio_costo = '{$precio_costo}' WHERE menuID = {$menuID} AND itemID = {$itemID} ";

			if ($sqlconnection->query($updateItemQuery) === TRUE) {
				echo '
			<script>
			swal("Buen Trabajo!", "Se edito con éxito", "success");
			</script>';
			} 

			else {
				//handle
				echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
				echo $sqlconnection->error;
				echo $updateItemQuery;
			}

		}
	} 

?>