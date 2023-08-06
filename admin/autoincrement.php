<?php

include("../functions.php");



if(isset($_POST['btnauto'])) {
	$empresacon =  "
	SELECT *
	FROM empresa WHERE id_empresa='1'
	";

	if ($orderResult = $sqlconnection->query($empresacon)) {
                        //if no order
		if ($orderResult->num_rows == 0) {

			echo "<tr><td class='text-center' colspan='7' >Actualmente no hay empresas registradas. </td></tr>";
		}

		else {
			while($fila2 = $orderResult->fetch_array(MYSQLI_ASSOC)) {
				$prefijo=$fila2['prefijo'];
			}
		}
	}
	$id_auto = $sqlconnection->real_escape_string($_POST['id_auto']);
	$quantity="0";
	$precio="0";
	$totalv="0";
	$item="0";
	
	$addOrderQuery = "INSERT INTO tbl_orderdetail (orderID ,itemID ,quantity,precio,totalv) VALUES ('{$id_auto}', '{$item}' ,'{$quantity}',$precio,$totalv)";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		echo "inserted.";
	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}

	$addOrderQuery = "INSERT INTO tbl_order (orderID ,prefijo,status ,order_date,pago,devolucion,id_cliente_fk) VALUES ('{$id_auto}' ,'$prefijo','En blanco' ,CURDATE(),'0','0','10' )";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		echo "inserted.";
	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}
	header("Location: index.php"); 
		
} 

?>