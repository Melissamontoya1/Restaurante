<?php 

if (isset($_POST['ingreso'])) {
	$fecha_ingreso = $sqlconnection->real_escape_string($_POST['fecha_ingreso']);
  	$descripcion_ingreso = $sqlconnection->real_escape_string($_POST['descripcion_ingreso']);
  	$estado_ingreso="general";
  	$valor_ingreso = $sqlconnection->real_escape_string($_POST['valor_ingreso']);



  	$addStaffQuery = "INSERT INTO ingresos_caja  (fecha_ingreso,descripcion_ingreso,estado_ingreso,valor_ingreso) VALUES ('{$fecha_ingreso}','{$descripcion_ingreso}','{$estado_ingreso}','{$valor_ingreso}') ";

  	if ($sqlconnection->query($addStaffQuery) === TRUE) {
  				echo '<script>
			swal("Buen Trabajo!", "Se registro con éxito", "success").then(function() {
				window.location.replace("caja_general.php");
				});

				</script>';
				exit();
  	
  	}else {
					//handle
  		echo "someting wong";
  		echo $sqlconnection->error;
  	}
  }


 ?>