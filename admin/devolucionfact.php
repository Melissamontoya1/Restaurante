<?php 
include("../functions.php");


$id= $_GET['orderID'];
session_start();
$_SESSION["orderID"]=$id;
$todo =  "
SELECT *
FROM tbl_order 
WHERE orderID='$id'";
if ($orderResult = $sqlconnection->query($todo)) {
                        //if no order
	if ($orderResult->num_rows == 0) {

		echo "ERROR";
	}

	else {
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$id=$orderRow['orderID'];
			$total=$orderRow['total'];
			$pago=$orderRow['pago'];
			$id_mesa=$orderRow['id_mesa_fk'];
		}

		$devolucion=($pago-$total);
		$addOrderQuery = "UPDATE tbl_order SET devolucion = '{$devolucion}' WHERE orderID = '{$id}'";  

		if ($sqlconnection->query($addOrderQuery) === TRUE) {
			echo "inserted.";
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
			header("Location: impresion-edit.php?orderID=$id");
		} 

		else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;

		}

	}
}



?>