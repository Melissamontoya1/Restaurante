<?php

if(isset($_POST['btneditc'])) {

	$id_cliente = $sqlconnection->real_escape_string($_POST['id_cliente']);
	$identificacion = $sqlconnection->real_escape_string($_POST['identificacion']);
	$nombres = $sqlconnection->real_escape_string($_POST['nombres']);
	$id_tipo_cliente = $sqlconnection->real_escape_string($_POST['id_tipo_cliente']);
	$direccion = $sqlconnection->real_escape_string($_POST['direccion']);
	$telefono = $sqlconnection->real_escape_string($_POST['telefono']);
	$correo = $sqlconnection->real_escape_string($_POST['correo']);

	$updateItemQuery = "UPDATE cliente SET identificacion = '{$identificacion}' , nombres = '{$nombres}' , id_tipo_cliente = '{$id_tipo_cliente}', direccion = '{$direccion}',telefono = '{$telefono}',correo = '{$correo}'  WHERE id_cliente = '{$id_cliente}'";

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

?>