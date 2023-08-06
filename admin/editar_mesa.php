<?php


if(isset($_POST['editMesa'])) {

	$id_mesa = $sqlconnection->real_escape_string($_POST['id_mesa']);
	$id_tipo_fk = $sqlconnection->real_escape_string($_POST['id_tipo_fk']);
	$id_area_fk = $sqlconnection->real_escape_string($_POST['id_area_fk']);
	$numero_mesa = $sqlconnection->real_escape_string($_POST['numero_mesa']);
	$estado_mesa = $sqlconnection->real_escape_string($_POST['estado_mesa']);


	$updateItemQuery = "UPDATE mesa SET id_tipo_fk = '{$id_tipo_fk}' , numero_mesa = '{$numero_mesa}' , estado_mesa = '{$estado_mesa}',id_area_fk = '{$id_area_fk}'  WHERE id_mesa = '{$id_mesa}'";

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