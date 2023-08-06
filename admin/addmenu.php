<?php
	
	//Add new menu (category)
	if (isset($_POST['addmenu'])) {

		if (!empty($_POST['menuname'])) {
			$menuname = $sqlconnection->real_escape_string($_POST['menuname']);

			$addMenuQuery = "INSERT INTO tbl_menu (menuName) VALUES ('{$menuname}')";

			if ($sqlconnection->query($addMenuQuery) === TRUE) {
				echo '<script>
					swal("Buen Trabajo!", "Se registro con éxito", "success");
					</script>';
			} else {
				echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
			}
		}

		//No input handle
		else {
			echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';

	}

}
?>