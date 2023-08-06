<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
$id_pago= $_GET['id_pago'];
$orderID= $_GET['orderID'];


$EliminarPago = "DELETE FROM pagos WHERE id_pago = {$id_pago}";

if ($sqlconnection->query($EliminarPago) === TRUE) { 
	$todo =  "
	SELECT *
	FROM tbl_order 
	WHERE orderID='{$orderID}'";
	if ($orderResult = $sqlconnection->query($todo)) {
                        //if no order
		if ($orderResult->num_rows == 0) {

			echo "ERROR";
		}

		else {
			while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
				$id=$orderRow['orderID'];
				$total=$orderRow['total'];
				$id_mesa=$orderRow['id_mesa_fk'];
			}
			$nuevo_estado="Habilitada";
			$editmesa = "UPDATE mesa SET estado_mesa = '{$nuevo_estado}' WHERE id_mesa = '{$id_mesa}'";  

			if ($sqlconnection->query($editmesa) === TRUE) {
				echo "inserted.";
			} 

			else {
				//handle
				echo "someting wong";
				echo $sqlconnection->error;

			}
			
		}


	}
	$pagosacum =  "
	SELECT *
	FROM pagos 
	WHERE orderID='{$orderID}'";
	if ($orderResultPago = $sqlconnection->query($pagosacum)) {
                        //if no order
		if ($orderResultPago->num_rows == 0) {

			echo "ERROR";
		}

		else {
			$acumPago=0;
			while($orderpago = $orderResultPago->fetch_array(MYSQLI_ASSOC)) {
				$pago_orden=$orderpago['pago_orden'];
				$acumPago+=$pago_orden;

			}
			
			
		}


	}
	
	//CONDICION  PARA SABER SI ESTA PAGA O A CREDITO
	if ($total>$acumPago) {
		$nuevo_estado_O="Credito";
		
	}else{
		
		$nuevo_estado_O="Vendido";
	}
	
	$editmesa = "UPDATE tbl_order SET status = '{$nuevo_estado_O}' WHERE orderID = '{$orderIDMesa}'";  

	if ($sqlconnection->query($editmesa) === TRUE) {
		echo "inserted.";
		echo $debe;
	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}


	?>		

	<script>
		swal("Buen Trabajo!", "Se elimino el pago con éxito", "success").then(function() {
			window.location.replace("consultar_fact.php?orderID=<?php echo $orderID; ?>");
		});

	</script>
	<?php  
	exit();
}else {
	echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error ", "error");</script>';
	echo $sqlconnection->error;
}
?>