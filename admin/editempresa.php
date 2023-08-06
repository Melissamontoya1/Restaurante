<?php


if(isset($_POST['btnedit'])) {
	$id_empresa = $sqlconnection->real_escape_string($_POST['id_empresa']);
	$nit_empresa = $sqlconnection->real_escape_string($_POST['nit_empresa']);
	$nombre_empresa = $sqlconnection->real_escape_string($_POST['nombre_empresa']);
	$direccion_empresa = $sqlconnection->real_escape_string($_POST['direccion_empresa']);
	$telefono_empresa = $sqlconnection->real_escape_string($_POST['telefono_empresa']);
	$resolucion = $sqlconnection->real_escape_string($_POST['resolucion']);
	$prefijo = $sqlconnection->real_escape_string($_POST['prefijo']);
	$factura_max = $sqlconnection->real_escape_string($_POST['factura_max']);
	$fecha_fin = $sqlconnection->real_escape_string($_POST['fecha_fin']);
	$impuesto = $sqlconnection->real_escape_string($_POST['impuesto']);
	$base = $sqlconnection->real_escape_string($_POST['base']);
	$nombre_impresora = $sqlconnection->real_escape_string($_POST['nombre_impresora']);
	$correo_empresa = $sqlconnection->real_escape_string($_POST['correo_empresa']);
	$fecha_inicio = $sqlconnection->real_escape_string($_POST['fecha_inicio']);
	$pago_directo = $sqlconnection->real_escape_string($_POST['pago_directo']);
	$porcentaje_propinas = $sqlconnection->real_escape_string($_POST['porcentaje_propinas']);
	
	$updateItemQuery = "UPDATE empresa SET nit_empresa = '$nit_empresa', nombre_empresa = '$nombre_empresa' , direccion_empresa = '$direccion_empresa', telefono_empresa = '$telefono_empresa', correo_empresa = '$correo_empresa', resolucion = '$resolucion',prefijo = '$prefijo',factura_max = '$factura_max',fecha_inicio = '$fecha_inicio',fecha_fin = '$fecha_fin',impuesto = '$impuesto',base = '$base',nombre_impresora='$nombre_impresora',pago_directo='$pago_directo',porcentaje_propinas='$porcentaje_propinas'  WHERE id_empresa = '$id_empresa'";

	if ($sqlconnection->query($updateItemQuery) === TRUE) {
		echo '<script>
		swal("Buen Trabajo!", "Se edito con éxito", "success").then(function() {
			window.location.replace("empresa.php");
			});

			</script>';
			exit();
		} 

		else {
				//handle
			echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al editar el archivo", "error");</script>';
			echo $sqlconnection->error;
			echo $updateItemQuery;
		}


	} 

?>