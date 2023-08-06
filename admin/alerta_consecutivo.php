<?php 
include("../functions.php");

$fecha_actual = date("d-m-Y");
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
			$factura_max=$fila2['factura_max'];
			$fecha_fin=$fila2['fecha_fin'];
			$prefijo=$fila2['prefijo'];

		}
	}
}
$order =  "
SELECT MAX(orderID) AS orderID
FROM tbl_order 
";

if ($orderResult = $sqlconnection->query($order)) {
                        //if no order
	if ($orderResult->num_rows == 0) {

		echo "ERROR";
	}

	else {
		while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$id_orden=$fila['orderID'];
		}
	}
}


$id_max=($factura_max-100);
//sumo 1 día echo date("d-m-Y",strtotime($fecha_actual."+ 1 days")); 
//resto 1 día
$fecha_comparacion= date("d-m-Y",strtotime($fecha_fin."- 15 days")); 

if ($fecha_actual >= $fecha_comparacion || $id_orden >= $id_max) {

	echo"
	<div class='alert alert-warning alert-dismissible'>
	<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
	<h5><i class='icon fas fa-exclamation-triangle'></i> ¡ATENCIÓN!</h5>
	Ya casi se cumple la resolución de la facturación.
	<table class='table table table-bordered'>
	<thead class='bg-danger text-center'>
	<th>Fecha de Vencimiento</th>
	<th>Prefijo</th>
	<th>Factura Maxima</th>
	<th>Factura Actual</th>

	</thead>
	<tbody class='text-center'>
	<tr>
	<td><B>".$fecha_fin."</B></td>
	<td><B>".$prefijo."</B></td>
	<td><B>".$factura_max."</B></td>
	<td><B>".$id_orden."</B></td>
	</tr>
	</tbody>
	</table>

	</div>
	

	";
}else{
	//echo "nada";
}


?>
