<?php 

if (isset($_POST['agregar_mesa'])) {

	$numero_mesa = $sqlconnection->real_escape_string($_POST['numero_mesa']);
	$estado_mesa = $sqlconnection->real_escape_string($_POST['estado_mesa']);
	$id_area_fk = $sqlconnection->real_escape_string($_POST['id_area_fk']);
$id_tipo_fk = $sqlconnection->real_escape_string($_POST['id_tipo_fk']);

	$addStaffQuery = "INSERT INTO mesa (numero_mesa ,estado_mesa,id_area_fk,id_tipo_fk) VALUES ('{$numero_mesa}','{$estado_mesa}','{$id_area_fk}','{$id_tipo_fk}') ";

	if ($sqlconnection->query($addStaffQuery) === TRUE) {
		echo '<script>
			swal("Buen Trabajo!", "Se registro con éxito", "success").then(function() {
				window.location.replace("mesa.php");
				});

				</script>';
				exit();

	}else {
					//handle
		echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
		echo $sqlconnection->error;
	}
}
if (isset($_POST['agregar_area'])) {
	$nombre_area = $sqlconnection->real_escape_string($_POST['nombre_area']);
	


	$addStaffQuery = "INSERT INTO area_mesa (nombre_area) VALUES ('{$nombre_area}') ";

	if ($sqlconnection->query($addStaffQuery) === TRUE) {
		echo '<script>
			swal("Buen Trabajo!", "Se registro con éxito", "success").then(function() {
				window.location.replace("mesa.php");
				});

				</script>';
				exit();

	}else {
					//handle
		echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
		echo $sqlconnection->error;
	}
}
if (isset($_POST['agregar_tipo'])) {
	$nombre_tipo = $sqlconnection->real_escape_string($_POST['nombre_tipo']);
	


	$addStaffQuery = "INSERT INTO tipo_mesa (nombre_tipo) VALUES ('{$nombre_tipo}') ";

	if ($sqlconnection->query($addStaffQuery) === TRUE) {
		echo '<script>
			swal("Buen Trabajo!", "Se registro con éxito", "success").then(function() {
				window.location.replace("mesa.php");
				});

				</script>';
				exit();

	}else {
					//handle
		echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
		echo $sqlconnection->error;
	}
}

?>