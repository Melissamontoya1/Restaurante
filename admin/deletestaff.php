<?php

include("../functions.php");


	//Deleting Item
if (isset($_GET['id_cliente'])) {

	$del_cliente = $sqlconnection->real_escape_string($_GET['id_cliente']);

	$deleteStaffQuery = "DELETE FROM cliente WHERE id_cliente = {$del_cliente}";

	if ($sqlconnection->query($deleteStaffQuery) === TRUE) {
		echo "Eliminado";
		header("Location: staff.php"); 
		exit();
	} 

	else {
				//handle


		echo "
		<center>
		<div class='col-md-8'>
		<br>
		<br>
		<br>
		<br>
		<br>
		<p class='btn-info'>Lo sentimos, no se puede eliminar este cliente debido a que esta ligado a una factura.</p>
		<a href='staff.php'><button class='btn btn-success'>Volver al Inicio </button></a>
		</div>
		</center>
		";
				//echo $sqlconnection->error;

	}
		//echo "<script>alert('{$del_menuID} & {$del_itemID}')</script>";
}
?>

