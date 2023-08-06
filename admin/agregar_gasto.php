<?php 


if (isset($_POST['agregar_gasto'])) {
	$fecha_gasto = $sqlconnection->real_escape_string($_POST['fecha_gasto']);
	$descripcion_gasto = $sqlconnection->real_escape_string($_POST['descripcion_gasto']);
	$valor_gasto = $sqlconnection->real_escape_string($_POST['valor_gasto']);


	$addStaffQuery = "INSERT INTO gastos (fecha_gasto ,descripcion_gasto ,valor_gasto) VALUES ('{$fecha_gasto}','{$descripcion_gasto}','{$valor_gasto}') ";

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