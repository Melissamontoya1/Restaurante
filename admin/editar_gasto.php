<?php
if(isset($_POST['editar_gasto'])) {

	$id_gasto = $sqlconnection->real_escape_string($_POST['id_gasto']);
	$fecha_gasto = $sqlconnection->real_escape_string($_POST['fecha_gasto']);
	$descripcion_gasto = $sqlconnection->real_escape_string($_POST['descripcion_gasto']);
	$valor_gasto = $sqlconnection->real_escape_string($_POST['valor_gasto']);

	$updateItemQuery = "UPDATE gastos SET fecha_gasto = '{$fecha_gasto}' , descripcion_gasto = '{$descripcion_gasto}' , valor_gasto = '{$valor_gasto}'  WHERE id_gasto = '{$id_gasto}'";

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