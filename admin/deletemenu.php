<?php

if (isset($_POST['deletemenu'])) {

		//Deleting Menu
	if (isset($_POST['menuID'])) {
		
		$menuID = $sqlconnection->real_escape_string($_POST['menuID']);

			//delete all item in this menu first
		$deleteMenuItemQuery = "DELETE FROM tbl_menuitem WHERE menuID = {$menuID}";

		if ($sqlconnection->query($deleteMenuItemQuery) === TRUE) {

				//them delete the menu after deleting all the item in this menu
			$deleteMenuQuery = "DELETE FROM tbl_menu WHERE menuID = {$menuID}";

			if ($sqlconnection->query($deleteMenuQuery) === TRUE) {
				echo '<script>
				swal("Buen Trabajo!", "Se registro con éxito", "success").then(function() {
					window.location.replace("menu.php");
					});
					
					</script>';
					exit();
				} 
				else {
					echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error ", "error");</script>';
					echo $sqlconnection->error;
				}
			} 

			else {
					//handle
				echo "ERROR 2";
				echo $sqlconnection->error;
			}
			//echo "<script>alert('{$del_menuID} & {$del_itemID}')</script>";
		}
	}
	

?>