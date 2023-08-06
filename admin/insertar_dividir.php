<?php
include("../functions.php");	
include ('empresa_datos.php');
include('includes/adminheader.php');
include ('includes/adminnav.php');

if (isset($_POST['dividir_boton'])) {
	$id_cliente_fk = $sqlconnection->real_escape_string($_POST['id_cliente_fk']);
	$id_mesa_fk = $sqlconnection->real_escape_string($_POST['id_mesa_fk']);
	$nombre_opcional = $sqlconnection->real_escape_string($_POST['nombre_opcional']);
	$id_mesa_principal_fk = $sqlconnection->real_escape_string($_POST['id_mesa_principal_fk']);
	$descuento = 0;
	$observacion_order = 0;
	$orderIDPrincipal = $sqlconnection->real_escape_string($_POST['orderIDPrincipal']);


	if (isset($_POST['itemID']) && isset($_POST['cantidad_dividir']) && isset($_POST['precio']) ) {

		$arrItemID = $_POST['itemID'];
		$arrItemQty = $_POST['cantidad_dividir'];	
		$arrPrecio = $_POST['precio'];	

		
		
			//check pair of the array have same element number
		if (count($arrItemID) == count($arrItemQty) && count($arrItemID)==count($arrPrecio)) {				
			$arrlength = count($arrItemID);


				//add new id
			$currentOrderID = getLastID("orderID","tbl_order") + 1;

			
			$total=0;
			for ($i=0; $i < $arrlength; $i++) { 
				insertOrderDetailQuery($currentOrderID,$arrItemID[$i] ,$arrItemQty[$i],$arrPrecio[$i],$orderIDPrincipal);
				descontar_licores($arrItemID[$i],$arrItemQty[$i]);
				$total+=$arrItemQty[$i]*$arrPrecio[$i];
			}
			

			insertOrderQuery($currentOrderID,$id_cliente_fk,$id_mesa_fk,$descuento,$observacion_order,$total,$id_mesa_principal_fk,$nombre_opcional);
			updateTotal($currentOrderID);
			updateIva($currentOrderID);
			updateSub($currentOrderID);

			echo '<script>
			swal("Buen Trabajo!", "Se registro con éxito", "success").then(function() {
				window.location.replace("./index.php");
				});

				</script>';
			
			
			//exit();
		}

		else {
			echo "xD";
		}
	}	
}

function insertOrderDetailQuery($orderID,$itemID,$quantity,$preciov,$orderIDPrincipal) {
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
	$restaDetalle = "SELECT * FROM tbl_orderdetail WHERE itemID='{$itemID}' AND orderID='{$orderIDPrincipal}'";

	if ($resultadoD = $sqlconnection->query($restaDetalle)) {
		
		while($rwDetalle = $resultadoD->fetch_array(MYSQLI_ASSOC)) { 
			
			$quantityResta=$rwDetalle['quantity'];

		}
	}
	$nuevaCantidad=$quantityResta-$quantity;
	$nuevoTotal=$nuevaCantidad*$preciov;
	$totalv=$preciov*$quantity;
	$totalIva=($totalv*$iva/100);
	$estado_producto="Pendiente";
	$editcantidad = "UPDATE tbl_orderdetail SET quantity= '{$nuevaCantidad}',totalv='{$nuevoTotal}' WHERE itemID='{$itemID}' AND orderID = '{$orderIDPrincipal}'";  

	if ($sqlconnection->query($editcantidad) === TRUE) {
		//echo "inserted.";

	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}
	updateTotal($orderIDPrincipal);
	$addOrderQuery = "INSERT INTO tbl_orderdetail (orderID ,itemID ,quantity,precio,totalv,iva,estado_producto) VALUES ('{$orderID}', '{$itemID}' ,'{$quantity}','{$preciov}',$totalv,$totalIva,'{$estado_producto}')";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {


		

	}else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}
}

function insertOrderQuery($orderID,$id_cliente_fk,$id_mesa_fk,$descuento,$observacion_order,$total,$id_mesa_principal_fk,$nombre_opcional) {
	global $sqlconnection;
	$devolucion="0";

	$prefijo = "SELECT * FROM empresa WHERE id_empresa='1'";

	if ($preResult = $sqlconnection->query($prefijo)) {
		$counter = 0;
		while($preRow = $preResult->fetch_array(MYSQLI_ASSOC)) { 
			$prefijo=$preRow['prefijo'];
			$impuesto=$preRow['impuesto'];
		}
	}
	//DEFINIR PAGO
	
	$estado="Pendiente de Pago";


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

	) VALUES ('{$orderID}','$prefijo','$estado','{$total}','{$id_cliente_fk}','$impuesto','{$id_mesa_fk}','{$descuento}','{$observacion_order}','{$id_mesa_principal_fk}','{$nombre_opcional}')";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		//echo "inserted.";
		//CAMBIAR ESTADO DE LA MESA
		$nuevo_estado="Deshabilitada";
		$editmesa = "UPDATE mesa SET estado_mesa = '{$nuevo_estado}' WHERE id_mesa = '{$id_mesa_fk}'";  

		if ($sqlconnection->query($editmesa) === TRUE) {
			//echo "inserted.";
			
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
