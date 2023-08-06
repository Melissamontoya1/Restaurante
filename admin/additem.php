<?php

	//Add new menu item
	if (isset($_POST['addItem'])) {

		if (!empty($_POST['itemName'])  && !empty($_POST['menuID'])) {
			$itemName = $sqlconnection->real_escape_string($_POST['itemName']);
			$itemPrice = $sqlconnection->real_escape_string($_POST['itemPrice']);
			$precio_costo = $sqlconnection->real_escape_string($_POST['precio_costo']);
			$stock = $sqlconnection->real_escape_string($_POST['stock']);
			$iva = $sqlconnection->real_escape_string($_POST['iva']);
			$menuID = $sqlconnection->real_escape_string($_POST['menuID']);

			$addItemQuery = "INSERT INTO tbl_menuitem (menuID ,menuItemName ,price,stock,iva,precio_costo) VALUES ({$menuID} ,'{$itemName}' ,'{$itemPrice}' ,'{$stock}',{$iva},{$precio_costo})";

			if ($sqlconnection->query($addItemQuery) === TRUE) {
				echo '<script>
					swal("Buen Trabajo!", "Se registro con éxito", "success");
					</script>';

			} 

			else {
				echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
				echo $sqlconnection->error;
			}
		}

		//No input handle
		else {
			echo "No esta capturando datos";
		}

	}

	
?>