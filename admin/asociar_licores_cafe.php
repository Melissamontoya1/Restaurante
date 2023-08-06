<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');

if(isset($_POST['insertar'])){
	$items1 = ($_POST['id_preparacion_fk']);
	$items2 = ($_POST['itemID_fk']);
	$items3 = ($_POST['cantidad_detalle']);

	while(true) {

				    //// RECUPERAR LOS VALORES DE LOS ARREGLOS ////////
		$item1 = current($items1);
		$item2 = current($items2);
		$item3 = current($items3);


				    ////// ASIGNARLOS A VARIABLES ///////////////////
		$id_preparacion_fk= (($item1 !== false) ? $item1 : ", &nbsp;");
		$itemID_fk= $item2;
		$cantidad_detalle=(( $item3 !== false) ? $item3 : ", &nbsp;");
		

				    //// CONCATENAR LOS VALORES EN ORDEN PARA SU FUTURA INSERCIÓN ////////
		$valores='('.$id_preparacion_fk.',"'.$itemID_fk.'","'.$cantidad_detalle.'"),';

				    //////// YA QUE TERMINA CON COMA CADA FILA, SE RESTA CON LA FUNCIÓN SUBSTR EN LA ULTIMA FILA /////////////////////
		$valoresQ= substr($valores, 0, -1);

				    ///////// QUERY DE INSERCIÓN ////////////////////////////
		$sql = "INSERT INTO detalle_preparacion (id_preparacion_fk, itemID_fk, cantidad_detalle) 
		VALUES $valoresQ";

		$sqlconnection->query($sql);
		    // Up! Next Value
		$item1 = next( $items1 );

		$item3 = next( $items3 );


				    // Check terminator
		if($item1 === false &&  $item3 === false ) break;

	}

}


?>
<script>

	$(function(){
				// Clona la fila oculta que tiene los campos base, y la agrega al final de la tabla
				$("#adicional").on('click', function(){
					$("#tabla tbody tr:eq(0)").clone().removeClass('fila-fija').appendTo("#tabla");
				});

				// Evento que selecciona la fila y la elimina 
				$(document).on("click",".eliminar",function(){
					var parent = $(this).parents().get(0);
					$(parent).remove();
				});
			});
		</script>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1>Preparaciones</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
								<li class="breadcrumb-item active">Inventario Licores y Cafe</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<!-- Main content -->
			<section class="content">
				<!-- Breadcrumbs-->
				<div class="container-fluid">
					<div id="accordion">
						<div class="card">
							<div class="card-header" id="headingOne">
								<h5 class="mb-0">
									<button class="btn btn-primary btn-block text-left" data-toggle="collapse" data-target="#collapseOne"  aria-controls="collapseOne">
										<i class="bi bi-arrow-repeat"></i>
										Agregar Preparación 
									</button>
								</h5>
							</div>

							<div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
								<div class="card-body">
									<form method="post">
										<input list="producto" name="itemID_fk[]" type="text" placeholder="Seleccione un Producto Ej: Coctel de Frutas" class="form-control">
										<datalist id="producto">
											<?php 
											$displayStaffQuery = "SELECT * FROM tbl_menuitem  ORDER BY itemID DESC";

											if ($result = $sqlconnection->query($displayStaffQuery)) {


												while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
													$itemID=$fila['itemID'];
													$menuItemName=$fila['menuItemName'];
													echo '<option value="'.$itemID.'">'.$menuItemName.'</option>';
												}
											}?>
										</datalist>

										<table class="table pt-2"  id="tabla">
											<tr class="fila-fija ">
												<td class="col-md-4">
													<input list="ingredientes" name="id_preparacion_fk[]" type="text" placeholder="Ingrediente Ej: Vodka" class="form-control">
													<datalist id="ingredientes">
														<?php 
														$preparacion = "SELECT *
														FROM stock_preparacion";

														if ($result2 = $sqlconnection->query($preparacion)) {


															while($fila2 = $result2->fetch_array(MYSQLI_ASSOC)) {
																$id_preparacion=$fila2['id_preparacion'];
																$nombre_preparacion=$fila2['nombre_preparacion'];
																echo '<option value="'.$id_preparacion.'">'.$nombre_preparacion.'</option>';
															}
														}
														?>
													</datalist>
												</td>	
												<td class="col-md-4"><input type="number" name="cantidad_detalle[]" class="form-control" placeholder="Cantidad de preparacion en Gramos o Mililitros"> </td>									
												<td class="eliminar col-md-4"><input type="button" class="btn btn-danger"  value="Menos -"/></td>
											</tr>
										</table>

										<div class="btn-der row">
											<button class="btn btn-success col-md-6" type="submit"  name="insertar"><i class="fas fa-save"></i> Guardar</button>
											<button id="adicional" name="adicional" type="button" class="btn btn-warning col-md-6"> <i class="fas fa-plus"></i> Más</button>

										</div>
									</form>
								</div>
							</div>
						</div>
					</div> 
					<div class="card">
						<div class="card-header bg-navy" id="headingOne">
							<i class="fas fa-list-alt"></i>
						Lista Actual de Preparaciones
						 </div>
					</div>

					<table class="display table table-bordered table-striped table-hover table-resposive">

						<thead>
							<tr class="info">
								<th class="col-md-2">Codigo</th>
								<th class="col-md-4">Nombre</th>
								<th class="col-md-2">Stock</th>
								<th class="col-md-4">Opciones</th>

							</tr>
						</thead>
						<tbody>

							<?php 
							$displayStaffQuery = "SELECT *
							FROM stock_preparacion
							ORDER BY id_preparacion ASC";

							if ($result = $sqlconnection->query($displayStaffQuery)) {


								while($fila = $result->fetch_array(MYSQLI_ASSOC)) {


									echo '<tr>
									<td>'.$fila['id_preparacion'].'</td>
									<td>'.$fila['nombre_preparacion'].'</td>
									<td>'.$fila['stock_preparacion'].'</td>
									<td>
									<button class="btn btn-warning"><i class="fas fa-edit"></i> Editar</button>
									<button class="btn btn-success"><i class="fas fa-plus"></i > Agregar Inventario</button>
									</td>
									</tr>';
								}
							}
							?>

						</tbody>
					</table>
				</div>

			</section>
		</div>
		<?php include ('includes/adminfooter.php');?>