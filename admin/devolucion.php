<?php 
include("../functions.php");

$max =  "
SELECT MAX(orderID) AS orderID
FROM tbl_order 
";

if ($orderResult = $sqlconnection->query($max)) {
                        //if no order
	if ($orderResult->num_rows == 0) {

		echo "ERROR";
	}

	else {
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$id=$orderRow['orderID'];
		}

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
				}

				$devolucion=($pago-$total);
				$addOrderQuery = "UPDATE tbl_order SET devolucion = '{$devolucion}' WHERE orderID = '{$id}'";  

				if ($sqlconnection->query($addOrderQuery) === TRUE) {
					echo "inserted.";
					header("Location: impresion-fact.php");
				} 

				else {
				//handle
					echo "someting wong";
					echo $sqlconnection->error;

				}

			}
		}
	}
}

?>