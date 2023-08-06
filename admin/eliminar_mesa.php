<?php

if (isset($_POST['eliminarMesa'])) {

	//CAPTURAR EL ID DE LA MESA
	if (isset($_POST['id_mesa'])) {
		
		$id_mesa = $sqlconnection->real_escape_string($_POST['id_mesa']);

			//ELIMINAR MESA
		$deleteMenuItemQuery = "DELETE FROM mesa WHERE id_mesa = {$id_mesa}";

		if ($sqlconnection->query($deleteMenuItemQuery) === TRUE) {		
			echo '<script>
			swal("Buen Trabajo!", "Se elimino con éxito", "success").then(function() {
				window.location.replace("mesa.php");
				});

				</script>';
				exit();
			}else {
				echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error ", "error");</script>';
				echo $sqlconnection->error;
			}
		}
	}


?>