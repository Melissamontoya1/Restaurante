<?php

if (isset($_POST['eliminarG'])) {

	//CAPTURAR EL ID DE LA MESA
	if (isset($_POST['id_gasto'])) {
		
		$id_gasto = $sqlconnection->real_escape_string($_POST['id_gasto']);

			//ELIMINAR MESA
		$deleteMenuItemQuery = "DELETE FROM gastos WHERE id_gasto = {$id_gasto}";

		if ($sqlconnection->query($deleteMenuItemQuery) === TRUE) {		
			echo '<script>
			swal("Buen Trabajo!", "Se Elimino con éxito", "success").then(function() {
				window.location.replace("gastos.php");
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