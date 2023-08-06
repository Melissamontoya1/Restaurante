<?php

include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<?php  
	$id_orden= $_GET['orderID'];
	$id_mesa= $_GET['id_mesa'];

	$cliente =  "
	SELECT  o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.total,o.descuento,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo,o.nombre_opcional
	FROM tbl_order o
	INNER JOIN tbl_orderdetail od
	ON o.orderID = od.orderID
	INNER JOIN tbl_menuitem mi
	ON od.itemID = mi.itemID
	INNER JOIN tbl_menu m
	ON mi.menuID = m.menuID
	INNER JOIN cliente c
	ON o.id_cliente_fk = c.id_cliente
	INNER JOIN mesa me
	ON o.id_mesa_fk = me.id_mesa
	INNER JOIN area_mesa a
	ON me.id_area_fk=a.id_area
	INNER JOIN tipo_mesa t 
	ON me.id_tipo_fk= t.id_tipo
	WHERE o.orderID='{$id_orden}' OR me.id_mesa='{$id_mesa}' ";

	if ($orderResult = $sqlconnection->query($cliente)) {

		$currentspan = 0;
		$total = 0;

                        //if no order
		if ($orderResult->num_rows == 0) {

			echo "<div class='alert alert-warning alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h5><i class='icon fas fa-exclamation-triangle'></i> Alert!</h5>
			Actualmente no hay pedido en este momento.
			</div>";
		}

		else {
			while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
				$id=$orderRow['orderID'];
				$nombres=$orderRow['nombres'];
				$pago=$orderRow['pago'];
				$devolucion=$orderRow['devolucion'];
				$total=$orderRow['total'];
				$id_cliente_fk=$orderRow['id_cliente_fk'];
				$tipo_mesa=$orderRow['nombre_tipo'];
				$numero_mesa=$orderRow['numero_mesa'];
				$identificacion=$orderRow['identificacion'];
				$nombre_cliente=$orderRow['nombres'];
				$nombre_opcional=$orderRow['nombre_opcional'];

			}
		}     
	}
	?>


	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="card-header bg-navy ">
				<!-- Page Content -->
				<h3>Factura # <?php echo $id; ?></h3> 
			</div>
			<div class="row">
				<div class="col-md-6">
					<table class="table table-sm table-bordered">
						<thead>
							<tr>
								<th style="width: 10px">#</th>
								<th>Detalles de la Factura</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>•</td>
								<td>Identificacion Cliente</td>
								<td>
									<?php echo $id_cliente_fk; ?>
								</td>
							</tr>
							<tr>
								<td>•</td>
								<td>Nombre Cliente</td>
								<td>
									<?php echo $nombres; ?> 
								</td>
							</tr>
							<tr>
								<td>•</td>
								<td>Nombre Opcional</td>
								<td>
									<?php echo $nombre_opcional; ?> 
								</td>
							</tr>
							<tr>
								<td>•</td>
								<td>Imprimir Factura</td>
								<td>
									<a href="impresion_detalle.php?orderID=<?php echo $id; ?>" class="btn btn-sm btn-info"><i class="fas fa-print"></i> Imprimir</a>
								</td>
							</tr>
							<tr>
								<td>•</td>
								<td>Total a pagar</td>
								<td>
									$ <?php echo number_format($total); ?>
								</td>
							</tr>

						</tbody>
					</table>
			<!-- ========================================================
					TABLA DE PRODUCTOS
					======================================================== -->
					<div class="col-md-12">
						<div class="card-header bg-success text-center
						">
						<h3> <?php echo $tipo_mesa."-".$numero_mesa; ?> 
					</h3>
				</div>
			</div>
			<input type="text" value="<?php echo $identificacion."  |  ".$nombre_cliente ?>" class="form-control" readonly>

			<table id="tblOrderList" class="table table-bordered text-center" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Precio</th>
						<th>Cant</th>
						<th>Total (COP)</th>
					</tr>
				</thead>
				<tbody id="tbody" style="overflow: scroll;" >
					<?php

					$sql_vendedor2=mysqli_query($sqlconnection,"SELECT  o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.total,o.descuento,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
						FROM tbl_order o
						INNER JOIN tbl_orderdetail od
						ON o.orderID = od.orderID
						INNER JOIN tbl_menuitem mi
						ON od.itemID = mi.itemID
						INNER JOIN tbl_menu m
						ON mi.menuID = m.menuID
						INNER JOIN cliente c
						ON o.id_cliente_fk = c.id_cliente
						INNER JOIN mesa me
						ON o.id_mesa_fk = me.id_mesa
						INNER JOIN area_mesa a
						ON me.id_area_fk=a.id_area
						INNER JOIN tipo_mesa t 
						ON me.id_tipo_fk= t.id_tipo
						WHERE o.orderID='{$id}' ");
					while ($rw=mysqli_fetch_array($sql_vendedor2)){    
						$descuento=$rw['descuento'];
						$total_final=$rw['total'];
						$total_desc=$rw['total_desc'];


						echo "<tr>

						<td>".$rw['menuName']." : ".$rw['menuItemName']."</td>
						<td>$ ".number_format($rw['price'])." </td>
						<td>".$rw['quantity']." </td>
						<td>$ ".number_format($rw['totalv'])."</td>

						</tr>
						";
					}
					?>

				</tbody>

			</table>




		</div>
	<!-- 	==========================================================
		TABLA DE PAGOS Y ABONOS
		========================================================== -->
		<div class="col-md-6">
			<table class="table table-bordered table-sm text-center">
				<thead class="bg-teal">
					<tr>
						<th colspan="6">Pagos Realizados</th>
					</tr>
					<tr>
						<td>Caja</td>
						<td>Valor Recibido</td>
						<td>Pago</td>
						<td>Devolución</td>
						<td>Fecha</td>
						<td>X</td>
					</tr>
				</thead>
				<tbody>
					<?php 
					$pagosacum =  "
					SELECT c.id_caja, c.nombre_caja, c.estado_caja,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, p.pago_orden, p.devuelto, p.fecha_pago
					FROM pagos p
					INNER JOIN tipo_caja c
					ON p.id_caja_fk=c.id_caja
					WHERE p.orderID='{$id_orden}'";
					if ($orderResultPago = $sqlconnection->query($pagosacum)) {
                        //if no order
						if ($orderResultPago->num_rows == 0) {

							//echo "ERROR";
						}

						else {
							$acumPago=0;
							while($orderpago = $orderResultPago->fetch_array(MYSQLI_ASSOC)) {
								$pago_orden=$orderpago['pago_orden'];
								$acumPago+=$pago_orden;
								echo "<tr>

								<td>".$orderpago['nombre_caja']." </td>
								<td>$ ".number_format($orderpago['monto_recibido'])." </td>
								<td>$ ".number_format($orderpago['pago_orden'])." </td>
								<td>$ ".number_format($orderpago['devuelto'])."</td>
								<td>".$orderpago['fecha_pago']."</td>
								";
								?>
								<td><a href="eliminar_pago.php?id_pago=<?php echo $orderpago['id_pago']; ?>&orderID=<?php echo $orderpago['orderID']; ?>" class="btn  btn-danger btn-block"><i class="fas fa-trash"></i></a></td>
								<?php  
								echo"
								</tr>
								";
							}


						}


					}
					
					?>
				</tbody>
			</table>
			<hr>
			<table class="table table-bordered table-sm text-center">
				<thead class="bg-primary">
					<tr>
						<th colspan="2">Propina</th>
					</tr>
					<tr>
						<td>Fecha Propina</td>
						<td>Valor Propina</td>
						
					</tr>
				</thead>
				<tbody>
					<?php 
					$acumPropinas =  "
					SELECT *
					FROM propinas
					WHERE cod_venta='{$id_orden}'";
					if ($orderResultPropina = $sqlconnection->query($acumPropinas)) {
                        //if no order
						if ($orderResultPropina->num_rows == 0) {

							//echo "ERROR";
						}

						else {
							$acumPropina=0;
							while($filaP = $orderResultPropina->fetch_array(MYSQLI_ASSOC)) {
								$valor_propina=$filaP['valor_propina'];
								$acumPropina+=$valor_propina;
								echo "<tr>

								<td>".$filaP['fecha_propina']." </td>
								<td>$ ".number_format($valor_propina)." </td>
								
								 
							
								</tr>
								";
							}
						}
					}
					$debe=(($acumPropina+$total)-$acumPago);
					?>
				</tbody>
			</table>
			<div class="col-md-12 text-center">

				<h4>Debe : <b>  $ <?php  echo number_format($debe); ?> </b></h4>
				<?php if ($debe>0){ ?>
					

					<form action="abonar.php" method="POST" class="formularioVenta">
						<label class="col-form-label ">Seleccione un método de pago</label>
						<table class="table pt-2"  id="tabla">
							<thead class="bg-teal">
								<tr>
									<th>Cuenta</th>
									<th>Vlr Recibido</th>
									<th>Pago</th>

									<th>X</th>
								</tr>
							</thead>
							<tr class="fila-fija ">

								<td class="col-md-3">
									<select class="form-control input-sm " name="id_caja_fk[]">

										<?php
										$sql_caja=mysqli_query($sqlconnection,"select * from tipo_caja WHERE estado_caja='Activo' order by id_caja");
										while ($rw=mysqli_fetch_array($sql_caja)){
											$id_caja=$rw["id_caja"];
											$nombre_caja=$rw["nombre_caja"];

											?>

											<option value="<?php echo $id_caja?>" name="id_caja_fk[]" class="id_caja"><?php echo $id_caja." - ".$nombre_caja?></option>
											<?php
										}
										?>
									</select>
								</td> 
								<td class=" col-md-3">
									<input type='hidden' value='<?php echo $id_orden ?>' name='orderID[]'>
									<input type='hidden' value='<?php echo $id_orden ?>' name='orderIDMesa'>

									<input type="number" name="monto_recibido[]" class="recibido form-control" placeholder="Dinero Recibido" required step="any" >

								</td>
								<td class=" col-md-3">
									<input type=" number" class="pago form-control" placeholder="Pago" pago="<?php echo $debe; ?>" value="<?php echo $debe; ?>" name="pago[]" max="<?php echo $debe; ?>"></td>

									<td class="eliminar col-md-1">
										<input type="button" class="btn btn-danger"  value="X"/>
									</td>
								</tr>

							</table>
							<input type="hidden" class="form-control input-lg" id="nuevoTotalVenta" name="nuevoTotalVenta" total="0" value="0" placeholder="0" readonly required>
							<input type="hidden" name="totalVenta" id="totalVenta" value="<?php echo $debe; ?>">
							<input type="text" name="totalVenta" id="totalVentaReal" value="<?php echo $debe; ?>"><br>
							<button id="adicional" name="adicional" type="button" class="adicional btn btn-warning col-md-6"> <i class="fas fa-plus"></i> Agregar mas Pagos</button>

							<div class="pt-2">
								<div class="form-group ">


									<input class="btn btn-success btn-block" type="submit" name="abonar" value="Pagar" id="botonPagar">
								</div>
							</div>

						</form>
					<?php } ?>
				</div>

			</div>



			<!-- /.container-fluid -->

			<!-- /.content-wrapper -->
		</div>
	</div>
</section>
</div>
</div>
</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
	<i class="fas fa-angle-up"></i>
</a>



<?php include ('includes/adminfooter.php');?>

