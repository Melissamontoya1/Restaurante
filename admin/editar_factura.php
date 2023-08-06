<?php
include("../functions.php");	


if (isset($_POST['editorder'])) {
	$orderID = $sqlconnection->real_escape_string($_POST['orderID']);
	$id_cliente_fk = $sqlconnection->real_escape_string($_POST['id_cliente_fk']);
	$id_mesa_fk = $sqlconnection->real_escape_string($_POST['id_mesa_fk']);
	$mesa_anterior = $sqlconnection->real_escape_string($_POST['mesa_anterior']);
		//CAMBIAR ESTADO DE LA MESA
	$nuevo_estado="Habilitada";
	$editmesa = "UPDATE mesa SET estado_mesa = '{$nuevo_estado}' WHERE id_mesa = '{$mesa_anterior}'";  

	if ($sqlconnection->query($editmesa) === TRUE) {
		echo "inserted.";

	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}
	
	
	if (isset($_POST['itemID']) && isset($_POST['itemqty'])) {
		$deleteStaffQuery = "DELETE FROM tbl_orderdetail WHERE orderID = {$orderID}";
		if ($sqlconnection->query($deleteStaffQuery) === TRUE) {
			$arrItemID = $_POST['itemID'];
			$arrItemQty = $_POST['itemqty'];	


			//check pair of the array have same element number
			if (count($arrItemID) == count($arrItemQty)) {				
				$arrlength = count($arrItemID);


			//add new id
				updateOrderQuery($orderID,$id_cliente_fk,$id_mesa_fk);

				for ($i=0; $i < $arrlength; $i++) { 
					insertOrderDetailQuery($orderID,$arrItemID[$i] ,$arrItemQty[$i]);
					//DevolverStock($arrItemID[$i] ,$arrItemQty[$i]);
				}

				updateTotal($orderID);
				updateSub($orderID);

				//completed insert current order
				header("Location: index.php");
				exit();
			}

			else {
				echo "xD";
			}
		}	
	}
} 

function insertOrderDetailQuery($orderID,$itemID,$quantity) {
	global $sqlconnection;
	$menuQuery = "SELECT * FROM tbl_menuitem WHERE itemID='{$itemID}'";

	if ($menuResult = $sqlconnection->query($menuQuery)) {
		$counter = 0;
		while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) { 
			$precio=$menuRow['price'];
			$precio_costo=$menuRow['precio_costo'];
		}
	}
	if ($precio_costo>0) {
		$utilidad_venta=($quantity*$precio_costo);
	}else{
		$utilidad_venta=0;
	}
	
	$totalv=$precio*$quantity;
	$estado_producto="Pendiente";
	$addOrderQuery = "INSERT INTO tbl_orderdetail (orderID ,itemID ,quantity,precio,totalv,precio_compra,utilidad_venta,estado_producto) VALUES ('{$orderID}', '{$itemID}' ,'{$quantity}',$precio,$totalv,$precio_costo,$utilidad_venta,'{$estado_producto}')";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		echo "inserted.";
	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}
}

function updateOrderQuery($orderID,$id_cliente_fk,$id_mesa_fk) {

	global $sqlconnection;
	$devolucion="0";
	$estado="Pendiente de Pago";
	$editOrderQuery = "UPDATE tbl_order SET status = '{$estado}' , id_cliente_fk = '{$id_cliente_fk}', id_mesa_fk = '{$id_mesa_fk}' WHERE orderID = '{$orderID}'";

	if ($sqlconnection->query($editOrderQuery) === TRUE) {
			//CAMBIAR ESTADO DE LA MESA
		$nuevo_estado="Deshabilitada";
		$editmesa = "UPDATE mesa SET estado_mesa = '{$nuevo_estado}' WHERE id_mesa = '{$id_mesa_fk}'";  

		if ($sqlconnection->query($editmesa) === TRUE) {
			echo "inserted.";
			
		} 

		else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;

		}
	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}
}


?>