<?php

if (isset($_POST['eliminarPago'])) {

	//CAPTURAR EL ID DE LA MESA
	if (isset($_POST['id_salida_propina'])) {
		
		$id_salida_propina = $sqlconnection->real_escape_string($_POST['id_salida_propina']);

			//ELIMINAR MESA
		$deleteMenuItemQuery = "DELETE FROM salidas_propinas WHERE id_salida = {$id_salida_propina}";

		if ($sqlconnection->query($deleteMenuItemQuery) === TRUE) {		
			echo '<script>
			swal("Buen Trabajo!", "Se elimino con éxito", "success").then(function() {
				window.location.replace("propinas.php");
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