<?php 

if (isset($_POST['addstaff'])) {
	if (!empty($_POST['staffname']) && !empty($_POST['staffrole'])) {
		$staffUsername = $sqlconnection->real_escape_string($_POST['staffname']);
		$staffRole = $sqlconnection->real_escape_string($_POST['staffrole']);
		$password = $sqlconnection->real_escape_string($_POST['password']);


		$addStaffQuery = "INSERT INTO tbl_staff (username ,password ,status ,role) VALUES ('{$staffUsername}' ,'{$password}' ,'Offline' ,'{$staffRole}') ";

		if ($sqlconnection->query($addStaffQuery) === TRUE) {
			echo '<script>
			swal("Buen Trabajo!", "Se registro con éxito", "success").then(function() {
				window.location.replace("empleado.php");
				});

				</script>';

			} 

			else {
					//handle
				echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
				echo $sqlconnection->error;
			}
		}
	}
?>