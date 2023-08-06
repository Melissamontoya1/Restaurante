<?php
include("../functions.php");	
include ('empresa_datos.php');


if (isset($_POST['sentorder'])) {
	$id_cliente_fk = $sqlconnection->real_escape_string($_POST['id_cliente_fk']);
	$id_mesa_fk = $sqlconnection->real_escape_string($_POST['id_mesa_fk']);
	$descuento = $sqlconnection->real_escape_string($_POST['descuento']);
	$observacion_order = $sqlconnection->real_escape_string($_POST['observacion_order']);
	$nombre_opcional = $sqlconnection->real_escape_string($_POST['nombre_opcional']);
	


	if (isset($_POST['itemID']) && isset($_POST['itemqty']) && isset($_POST['precio']) ) {

		$arrItemID = $_POST['itemID'];
		$arrItemQty = $_POST['itemqty'];	
		$arrPrecio = $_POST['precio'];	

		
		
			//check pair of the array have same element number
		if (count($arrItemID) == count($arrItemQty) && count($arrItemID)==count($arrPrecio)) {				
			$arrlength = count($arrItemID);


				//add new id
			$currentOrderID = getLastID("orderID","tbl_order") + 1;

			
			$total=0;
			for ($i=0; $i < $arrlength; $i++) { 
				insertOrderDetailQuery($currentOrderID,$arrItemID[$i] ,$arrItemQty[$i],$arrPrecio[$i]);
				descontar_licores($arrItemID[$i],$arrItemQty[$i]);
				$total+=$arrItemQty[$i]*$arrPrecio[$i];
			}
			

			insertOrderQuery($currentOrderID,$id_cliente_fk,$id_mesa_fk,$descuento,$observacion_order,$total,$nombre_opcional);
			updateTotal($currentOrderID);
			updateIva($currentOrderID);
			updateSub($currentOrderID);
			


			if ($pago_directo=="No") {
					//completed insert current order
				header("Location: kitchen.php");
			}else{
					//completed insert current order
				header("Location: pagar_fact.php?orderID=$currentOrderID");
			}
			
			exit();
		}

		else {
			echo "xD";
		}
	}	
}

function insertOrderDetailQuery($orderID,$itemID,$quantity,$preciov) {
	global $sqlconnection;
	$menuQuery = "SELECT * FROM tbl_menuitem WHERE itemID='{$itemID}'";

	if ($menuResult = $sqlconnection->query($menuQuery)) {
		$counter = 0;
		while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) { 
			$precio=$menuRow['price'];
			$stock=$menuRow['stock'];
			$iva=$menuRow['iva'];
		}
	}
	
	$totalv=$preciov*$quantity;
	$totalIva=($totalv*$iva/100);
	$estado_producto="Pendiente";

	$addOrderQuery = "INSERT INTO tbl_orderdetail (orderID ,itemID ,quantity,precio,totalv,iva,estado_producto) VALUES ('{$orderID}', '{$itemID}' ,'{$quantity}','{$preciov}',$totalv,$totalIva,'{$estado_producto}')";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		echo "inserted.";

	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}
}

function insertOrderQuery($orderID,$id_cliente_fk,$id_mesa_fk,$descuento,$observacion_order,$total,$nombre_opcional) {
	global $sqlconnection;
	$devolucion="0";
	$id_mesa_principal_fk=$id_mesa_fk;
	$prefijo = "SELECT * FROM empresa WHERE id_empresa='1'";

	if ($preResult = $sqlconnection->query($prefijo)) {
		$counter = 0;
		while($preRow = $preResult->fetch_array(MYSQLI_ASSOC)) { 
			$prefijo=$preRow['prefijo'];
			$impuesto=$preRow['impuesto'];
		}
	}
	//DEFINIR PAGO
	if ($pago_directo=="Si") {
		$estado="Pendiente de Pago";
		
	}else{
		$estado="Esperando";
		
	}

	$addOrderQuery = "INSERT INTO tbl_order (
		orderID,
		prefijo,
		status,
		total,
		id_cliente_fk,
		impo,
		id_mesa_fk,
		descuento,
		observacion_order,
		id_mesa_principal_fk,
		nombre_opcional

	) VALUES ('{$orderID}','$prefijo','$estado','{$total}','{$id_cliente_fk}','$impuesto','{$id_mesa_fk}','{$descuento}','{$observacion_order}','$id_mesa_principal_fk','{$nombre_opcional}')";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		echo "inserted.";
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
