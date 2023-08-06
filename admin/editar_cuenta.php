<?php

if(isset($_POST['editar_cuenta'])) {

	$id_caja = $sqlconnection->real_escape_string($_POST['id_caja']);
	$nombre_caja = $sqlconnection->real_escape_string($_POST['nombre_caja']);
	$estado_caja = $sqlconnection->real_escape_string($_POST['estado_caja']);


	$updateItemQuery = "UPDATE tipo_caja SET nombre_caja = '{$nombre_caja}' , estado_caja = '{$estado_caja}'  WHERE id_caja = '{$id_caja}'";

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

?>