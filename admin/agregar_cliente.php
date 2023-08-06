<?php 

if (isset($_POST['btnagregarc'])) {
	$id_cliente = $sqlconnection->real_escape_string($_POST['id_cliente']);
	$identificacion = $sqlconnection->real_escape_string($_POST['identificacion']);
	$nombres = $sqlconnection->real_escape_string($_POST['nombres']);
	$id_tipo_cliente = $sqlconnection->real_escape_string($_POST['id_tipo_cliente']);
	$direccion = $sqlconnection->real_escape_string($_POST['direccion']);
	$telefono = $sqlconnection->real_escape_string($_POST['telefono']);
	$correo = $sqlconnection->real_escape_string($_POST['correo']);

	$addStaffQuery = "INSERT INTO cliente (identificacion ,nombres ,id_tipo_cliente ,direccion,telefono,correo) VALUES ('{$identificacion}','{$nombres}','{$id_tipo_cliente}','{$direccion}','{$telefono}','{$correo}') ";

	if ($sqlconnection->query($addStaffQuery) === TRUE) {
		echo '
			<script>
			swal("Buen Trabajo!", "Se registro con éxito", "success");
			</script>';

	}else {
					//handle
		echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
		echo $sqlconnection->error;
	}
}
?>