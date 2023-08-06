<?php
include("../functions.php");



if (isset($_POST['status']) && isset($_POST['orderID'])) {
	$estado = $sqlconnection->real_escape_string($_POST['status']);
	$orderID = $sqlconnection->real_escape_string($_POST['orderID']);
	$estado=$_POST['status'];

	$estadomesa="SELECT *
	FROM tbl_order WHERE orderID = {$orderID}";
	if ($orderResult2 = $sqlconnection->query($estadomesa)) {
		if ($orderResult2->num_rows == 0) {

			echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
		}

		else {

			while($fila2 = $orderResult2->fetch_array(MYSQLI_ASSOC)) {
				$id_mesa_fk=$fila2['id_mesa_fk'];
			}
		}
	}

	if ($estado=="Cancelado") {
		$nuevo_estado="Habilitada";
		$editmesa = "UPDATE mesa SET estado_mesa = '$nuevo_estado' WHERE id_mesa = '$id_mesa_fk'";  

		if ($sqlconnection->query($editmesa) === TRUE) {
			echo "inserted.";

		}else {
				//handle
			echo "Error al cambiar el estadod e la mesa";
			echo $sqlconnection->error;

		}
		$eliminarDetalle = "DELETE FROM tbl_orderdetail WHERE orderID = {$orderID}";

		if ($sqlconnection->query($eliminarDetalle) === TRUE) {
			$EliminarOrden = "DELETE FROM tbl_order WHERE orderID = {$orderID}";

			if ($sqlconnection->query($EliminarOrden) === TRUE) {
				echo "deleted."; 
//CAMBIAR ESTADO DE LA MESA
				
				exit();
			}else {
				//handle
				echo "ERROR AL ELIMINAR LA ORDEN ESTADO CANCELAR";
				echo $sqlconnection->error;
			}
		}else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;
		}
	}else{
		$status = $sqlconnection->real_escape_string($_POST['status']);
		$orderID = $sqlconnection->real_escape_string($_POST['orderID']);

		$addOrderQuery = "UPDATE tbl_order SET status = '{$status}' WHERE orderID = {$orderID};";

		if ($sqlconnection->query($addOrderQuery) === TRUE) {
			echo "inserted.";
		} 

		else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;

		}

	}



}

if (isset($_GET['orderID'])) {

	$status = "Entregado";
	$orderID = $sqlconnection->real_escape_string($_GET['orderID']);

	$addOrderQuery = "UPDATE tbl_order SET status = '{$status}' WHERE orderID = {$orderID};";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		echo "inserted.";
		header("Location: index.php");
	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}

}



?>