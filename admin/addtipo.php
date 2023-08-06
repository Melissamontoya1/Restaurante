<?php 
include("../functions.php");



if (isset($_POST['tipocliente'])) {

	$tipo = $sqlconnection->real_escape_string($_POST['tipo']);



	$addStaffQuery = "INSERT INTO tipo_cliente (descripcion) VALUES ('{$tipo}')";

	if ($sqlconnection->query($addStaffQuery) === TRUE) {
		echo "added.";
		header("Location: staff.php"); 
		exit();

	} 

	else {
					//handle
		echo "someting wong";
		echo $sqlconnection->error;
	}

}
?>