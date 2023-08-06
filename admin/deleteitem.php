<?php

include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');


	//Deleting Item
if (isset($_GET['menuID']) && isset($_GET['itemID'])) {

	$del_menuID = $sqlconnection->real_escape_string($_GET['menuID']);
	$del_itemID = $sqlconnection->real_escape_string($_GET['itemID']);

	$deleteItemQuery = "DELETE FROM tbl_menuitem WHERE menuID = {$del_menuID} AND itemID = {$del_itemID}";

	if ($sqlconnection->query($deleteItemQuery) === TRUE) {
		echo '<script>
			swal("Buen Trabajo!", "Se elimino con éxito", "success").then(function() {
				window.location.replace("menu.php");
				});

				</script>';
	} 

	else {
		echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
		echo $sqlconnection->error;

	}
		//echo "<script>alert('{$del_menuID} & {$del_itemID}')</script>";
}
?>