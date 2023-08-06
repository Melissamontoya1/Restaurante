<?php 


include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
include("additem.php");
include("deletemenu.php");
include("addmenu.php");
include("edititem.php");
include("empresa_datos.php");

$id=$_GET['id'];
echo $id;
$sql_vendedor=mysqli_query($sqlconnection,"SELECT  o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,o.descuento,o.total,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
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
	WHERE o.orderID='{$id}' OR o.id_mesa_fk='{$id_mesa}'");
while ($rw1=mysqli_fetch_array($sql_vendedor)){ 
	$idfact=$rw1['orderID'];
	$tipo_mesa=$rw1['nombre_tipo'];
	$id_mesaP=$rw1['id_mesa_fk'];
	$numero_mesa=$rw1['numero_mesa'];
	$nombre_cliente=$rw1['nombres'];
	$identificacion=$rw1['identificacion'];
	$id_cliente_fk=$rw1['id_cliente'];
	$total=$rw1['total'];
	$descuento=$rw1['descuento'];
	$fecha=$rw1['order_date'];
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header ">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Dividir Mesas</h1>
				</div>
				<div class="col-sm-6 ">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
						<li class="breadcrumb-item active">Dividir</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">

				<div class="col-lg-12 col-md-12" >

					<div class="card mb-3" >
						
						<div class="card-header bg-navy">
							<h3>
								Mesa Principal - Orden # <?php echo $idfact ?> | <?php echo $tipo_mesa."-".$numero_mesa; ?>
							</h3>
						</div>
						<div class="card-body">
							<form action="insertar_dividir.php" method="POST" >
								<input type="hidden" value="<?php echo $idfact; ?>" name="orderIDPrincipal">
								<input type="hidden" value="<?php echo $id_mesaP; ?>" name="id_mesa_principal_fk">
								<label for="">Seleccionar Cliente</label>
								<select class="form-control input-sm id_cliente_fk" name="id_cliente_fk">
									<option value="<?php echo $id_cliente_fk; ?>" selected><?php echo $identificacion." - ".$nombre_cliente?></option>
									<?php
									$sql_vendedor=mysqli_query($sqlconnection,"select * from cliente order by nombres");
									while ($rw=mysqli_fetch_array($sql_vendedor)){
										$id_cliente=$rw["id_cliente"];
										$nombre_cliente=$rw["nombres"];
										$identificacion=$rw["identificacion"];

										?>

										<option value="<?php echo $id_cliente?>" name="id_cliente_fk" class="id_cliente_fk"><?php echo $identificacion." - ".$nombre_cliente?></option>
										<?php
									}
									?>
								</select>
								<label for="">Nombre Opcional (Apodo) </label>
								<input type="text" value="" class="form-control" placeholder="Nombre opcional" name="nombre_opcional">
								<select class="form-control input-sm " name="id_mesa_fk" >

									<?php
									$sql_mesa=mysqli_query($sqlconnection,"SELECT m.id_mesa,m.numero_mesa,m.estado_mesa,m.id_area_fk,m.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
										FROM mesa m
										INNER JOIN area_mesa a
										ON m.id_area_fk=a.id_area
										INNER JOIN tipo_mesa t 
										ON m.id_tipo_fk= t.id_tipo WHERE m.estado_mesa='Habilitada' AND a.nombre_area='Mesa Dividida'");
									while ($rw=mysqli_fetch_array($sql_mesa)){
										$id_mesa=$rw["id_mesa"];
										$id_tipo=$rw["id_tipo"];
										$nombre_tipo=$rw["nombre_tipo"];
										$nombre_area=$rw["nombre_area"];
										$numero_mesa=$rw["numero_mesa"];

										?>

										<option value="<?php echo $id_mesa?>" name="id_mesa_fk" class="id_mesa_fk"><?php echo $nombre_tipo." # ".$numero_mesa."  / Area: ".$nombre_area ?></option>
										<?php
									}
									?>
								</select>
								<table id="tblOrderList" class="table table-bordered text-center" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Mover</th>
											<th>Nombre</th>
											<th>Precio</th>
											<th>Cant</th>
											
											<th>Cant</th>
										</tr>
									</thead>
									<tbody style="overflow: scroll;" >
										<?php

										$sql_vendedor2=mysqli_query($sqlconnection,"SELECT  o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.total,o.descuento,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo,od.orderDetailID
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
											$canidad_dividir=$rw['quantity'];
											echo "<tr class='text-center'>
											<input type='hidden' value='".$rw['orderDetailID']."' name='orderDetailID' class='orderDetailID'>
											<input type='hidden' value='".$rw['orderID']."' name='orderID' class='orderID'>
											<input type = 'hidden' name = 'itemID[]' value ='".$rw['itemID']."' class='itemID' />
											<td><button class='btn btn-danger deleteBtn btn-sm' type='button' onclick='

											event.preventDefault();
											$(this).closest('tr').remove();
											}()'><i class='fas fa-times'></i></button></td>
											<td>".$rw['menuName']." : ".$rw['menuItemName']."</td>
											<td>".$rw['price']."</td>
											<td hidden><input type = 'hidden' required='required' name = 'precio[]' width='10px' class='form-control' value ='".$rw['price']."'  /></td>
											<td>".$rw['quantity']."</td>
											
											
											<td><input type='number' name='cantidad_dividir[]' value ='".$canidad_dividir."' class='form-control cantidad_dividir' min='1' max='".$canidad_dividir."'> 
											</td>
											</tr>
											";
										}
										?>

									</tbody>

								</table>
								<center>
									<div class="esconderTotal">
										<p ><h4><B>Total a Pagar: $ <?php echo number_format($total_final);?></B></h4></p><br>
									</div>
								</center>

								<button type="submit" class="btn btn-primary btn-block dividir_boton" name="dividir_boton">Dividir Mesa</button>
							</form>
						</div>
					</div>
				

				</div>
				<!-- /.container-fluid -->
			</div>

		</section>
		<!-- /.content-wrapper -->

	</div>


	<script>

		$(document).on('click','.deleteBtn', function(event){

			var Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000
			});


			Toast.fire({
				icon: 'error',
				title: 'Producto eliminado de la lista.'
			})

			event.preventDefault();
			$(this).closest('tr').remove();
			$(".esconderTotal").hide();
		});
	</script>
	<?php include ('includes/adminfooter.php');?>
