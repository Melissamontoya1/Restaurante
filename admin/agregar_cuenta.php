<?php 
include("../functions.php");
if (isset($_POST['agregar_cuenta'])) {
  	$nombre_caja = $sqlconnection->real_escape_string($_POST['nombre_caja']);
  	$estado_caja = $sqlconnection->real_escape_string($_POST['estado_caja']);



  	$addStaffQuery = "INSERT INTO tipo_caja (nombre_caja,estado_caja) VALUES ('{$nombre_caja}','{$estado_caja}') ";

  	if ($sqlconnection->query($addStaffQuery) === TRUE) {
  		echo "Cuenta agregada con exito.";
		header("Location: cuentas.php"); 
		exit();
  		

  	}else {
					//handle
  		echo "someting wong";
  		echo $sqlconnection->error;
  	}
  }


 ?>