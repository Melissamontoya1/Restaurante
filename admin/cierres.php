<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
//include("base_dia.php");
//GANANCIAS TOTALES

$fecha_total=date("Y-m-d");
$fecha_editar=$fecha_total;

$total_ventas =  "
SELECT pago_orden,fecha_pago
FROM pagos WHERE fecha_pago ='{$fecha_editar}'
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
$base=0;
$totalVentas=0;
$totalGastos=0;
$ultilidadCierre=0;



$fecha_actual=date("Y-m-d");
$cierre =  "
SELECT *
FROM base WHERE fecha_base='$fecha_actual'
";

if ($orderResult = $sqlconnection->query($cierre)) {
                        //if no order
	if ($orderResult->num_rows == 0) {
		echo "nada";
	}else {
		
		while($cierreFinal = $orderResult->fetch_array(MYSQLI_ASSOC)) { 
			$base=$cierreFinal['valor_base'];
			$totalVentas=$cierreFinal['ventas_cierre'];
			$totalGastos=$cierreFinal['gastos_cierre'];
			$ultilidadCierre=$cierreFinal['utilidad_cierre'];
			
		}
	}
}

?>





<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Información Cierres</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
						<li class="breadcrumb-item active">Cierres</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
<div class="container-fluid">
	<div class="card mb-3">
		<div class="card-header bg-success">
			<i class="fas fa-chart-area"></i>
			Informe del dia
		</div>
		<div class="card-body">
			<table class="table table-bordered " width="100%" cellspacing="0">
				<tbody>
					<tr>
						<td>Base</td>
						<td>COP $ <?php echo number_format($base); ?></td>
					</tr>
					<tr>
						<td>Ventas</td>
						<td>COP $ <?php echo getSalesGrandTotal("DAY"); ?></td>
					</tr>
					<tr >
						<td>Gastos</td>
						<td>COP $ <?php echo number_format($totalGastos); ?></td>
					</tr>
					<tr class="bg-info">
						<td><b>Total en Caja</b></td>
						<td><b>COP $ <?php echo number_format($ultilidadCierre); ?></b></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- TABLA DE CONSULTAS DE FACTURAS -->
<div class="col-md-12">
	<div class="col-lg-12 ">
		<div class="card mb-3">
			<div class="card-header bg-navy">
				<i class="fas fa-chart-area"></i>
			Lista de Órdenes de Ventas</div>
			<div class="card-body">
				<table  class="display table table-bordered " width="100%" cellspacing="0">
					<thead>								
						<th>Fecha Cierre</th>
						<th>Base</th>
						<th>Total Ventas</th>
						<th class='text-center'>Total Gastos</th>
						<th class='text-center'>Total Cierre</th>

						<th class='text-center'>Btn</th>
					</thead>
					<tbody>
						<?php 
						$consultaCierres =  "
						SELECT *
						FROM base
						ORDER BY fecha_base DESC
						";

						if ($orderResult = $sqlconnection->query($consultaCierres)) {

							if ($orderResult->num_rows == 0) {

								echo "<tr><td class='text-center' colspan='9' >Actualmente no hay pedidos por pagar. </td></tr>";

							}

							else {
								while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
									$id_base=$orderRow['id_base'];
									$fecha_base=$orderRow['fecha_base'];
									$valor_base=$orderRow['valor_base'];
									$ventas_cierre=$orderRow['ventas_cierre'];
									$gastos_cierre=$orderRow['gastos_cierre'];
									$utilidad_cierre=$orderRow['utilidad_cierre'];
									echo"
									<tr>
									<td>".$fecha_base."</td>
									<td>".number_format($valor_base)."</td>
									<td>".number_format($ventas_cierre)."</td>
									<td>".number_format($gastos_cierre)."</td>
									<td>".number_format($utilidad_cierre)."</td>
									";?>
									<td>
										<center>
											<a href="tirillacierre.php?fechaTirilla=<?php echo $orderRow['fecha_base']; ?>" class="btn btn-sm btn-info"><i class="fa fa-print"></i></a>
										</center>
									</td>
								</tr>

								<?php 
							}


						}
					}

					?>
				</tbody>
			</table>
		</div>
	</div>
	

	<!-- /.container-fluid -->

	<!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->
</section>

</div>
<!-- /#wrapper -->


<?php include ('includes/adminfooter.php');?>
