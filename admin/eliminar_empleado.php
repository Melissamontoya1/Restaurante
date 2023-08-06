<?php

include("../functions.php");
include('includes/adminheader.php');


if (isset($_GET['staffID'])) {

	$del_staffID = $sqlconnection->real_escape_string($_GET['staffID']);

	$deleteStaffQuery = "DELETE FROM tbl_staff WHERE staffID = {$del_staffID}";

	if ($sqlconnection->query($deleteStaffQuery) === TRUE) {
		echo '<script>
		swal("Buen Trabajo!", "Se elimino con éxito", "success").then(function() {
			window.location.replace("empleado.php");
			});

			</script>';
			exit();
		} 

		else {
				//handle
			echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
			echo $sqlconnection->error;

		}
		//echo "<script>alert('{$del_menuID} & {$del_itemID}')</script>";
	}
?>