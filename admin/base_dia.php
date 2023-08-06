<?php 

$id_empresa="1";
$empresacon =  "
SELECT base
FROM empresa WHERE id_empresa='$id_empresa'
";

if ($orderResult = $sqlconnection->query($empresacon)) {
                        //if no order
	if ($orderResult->num_rows == 0) {

		echo "<tr><td class='text-center' colspan='7' >Actualmente no hay empresas registradas. </td></tr>";
	}

	else {
		while($fila2 = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$bases=$fila2['base'];
		}
	}
}

$fecha_actual=date("Y-m-d");
$comprobar_fecha =  "
SELECT *
FROM base WHERE fecha_base='$fecha_actual'
";

if ($orderResult = $sqlconnection->query($comprobar_fecha)) {
                        //if no order
	if ($orderResult->num_rows == 0) {

		$base = "INSERT INTO base (fecha_base,valor_base)VALUES('{$fecha_actual}',$bases)";

		if ($sqlconnection->query($base) === TRUE) {
			
			exit();
		} 

		else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;
			echo $updateItemQuery;
		}
	}else {
		//echo "ERROR AL INGRESAR BASE";
	}
}

//GANANCIAS TOTALES

$fecha_total=date("Y-m-d");
$fecha_editar=$fecha_total;

$total_ventas =  "
SELECT pago_orden,fecha_pago
FROM pagos WHERE fecha_pago='$fecha_editar'
";

if ($orderResult = $sqlconnection->query($total_ventas)) {
                        //if no order
	if ($orderResult->num_rows == 0) {
				//EDITAR Cierre
		$resultado=0;
		$updateItemQuery = "UPDATE base SET ventas_cierre='{$resultado}'  WHERE fecha_base = '$fecha_actual'";

		if ($sqlconnection->query($updateItemQuery) === TRUE) {


		}else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;
			echo $updateItemQuery;
		}
	}else {
		$acumVentas=0;
		while($ventas = $orderResult->fetch_array(MYSQLI_ASSOC)) { 
			$totalve=$ventas['pago_orden'];
			$acumVentas+=$totalve;
		}
		
		$totalV=$acumVentas;
		$resultado=$totalV;
		//EDITAR GASTOS
		$updateItemQuery = "UPDATE base SET ventas_cierre='{$resultado}'  WHERE fecha_base = '$fecha_actual'";

		if ($sqlconnection->query($updateItemQuery) === TRUE) {


		}else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;
			echo $updateItemQuery;
		}
	}
	
}

//GASTOS TOTALES


$total_gasto =  "
SELECT valor_gasto
FROM gastos WHERE fecha_gasto='$fecha_editar'
";

if ($orderResult = $sqlconnection->query($total_gasto)) {
                        //if no order
	$acumGasto=0;
	if ($orderResult->num_rows == 0) {
				//EDITAR GASTOS
		$resultadoG=0;
		$updateItemQuery = "UPDATE base SET gastos_cierre='{$resultadoG}'  WHERE fecha_base = '$fecha_actual'";

		if ($sqlconnection->query($updateItemQuery) === TRUE) {


		}else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;
			echo $updateItemQuery;
		}
	}else {
		
		while($gasto = $orderResult->fetch_array(MYSQLI_ASSOC)) { 
			$totalG=$gasto['valor_gasto'];
			$acumGasto+=$totalG;
		}
		
		$totalGAS=$acumGasto;
		$resultadoG=$totalGAS;
		//EDITAR GASTOS
		$updateItemQuery = "UPDATE base SET gastos_cierre='{$resultadoG}'  WHERE fecha_base = '$fecha_actual'";

		if ($sqlconnection->query($updateItemQuery) === TRUE) {


		}else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;
			echo $updateItemQuery;
		}
	}
	


	$subtotal=($acumVentas+$bases);
	$ganancia=$subtotal-$acumGasto;
//EDITAR GASTOS
	$updateItemQuery = "UPDATE base SET utilidad_cierre='{$ganancia}'  WHERE fecha_base = '$fecha_actual'";

	if ($sqlconnection->query($updateItemQuery) === TRUE) {


	}else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;
		echo $updateItemQuery;
	}
}
?>