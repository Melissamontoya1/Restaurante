<?php

if (isset($_POST['eliminarC'])) {

	//CAPTURAR EL ID DE LA MESA
	if (isset($_POST['id_correo'])) {
		
		$id_correo = $sqlconnection->real_escape_string($_POST['id_correo']);

			//ELIMINAR MESA
		$deleteMenuItemQuery = "DELETE FROM correos WHERE id_correos = {$id_correo}";

		if ($sqlconnection->query($deleteMenuItemQuery) === TRUE) {		
			echo '<script>
			swal("Buen Trabajo!", "Se Elimino con éxito", "success").then(function() {
				window.location.replace("empresa.php");
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