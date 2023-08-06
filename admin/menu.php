<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
include("additem.php");
include("deletemenu.php");
include("addmenu.php");
include("edititem.php");
include("empresa_datos.php");
include("addentrada.php");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header ">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Administrar Menú</h1>
				</div>
				<div class="col-sm-6 ">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
						<li class="breadcrumb-item active">Menú</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">


			<!-- Page Content -->
			<div class="row">
				<div class="col-md-10">
					
				</div>
				<div class="col-md-2">
					<a href="descargar_inventario.php"><button class="btn btn-success btn- float-right" >Descargar Inventario</button></a>
				</div>	
			</div>
			
			<hr>
			<p>Aqui vas a poder Administrar los productos de tu negocio, puedes Agregar, Modificar o Eliminar listas.</p>

			<div class="card mb-3 border-primary">
				<div class="card-header">
					<i class="fas fa-chart-area"></i>
					Lista de Productos
					<button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addMenuModal">Agregar Categoría</button>
					<p><B>Selecciona la categoria que quieres ver, para listar los productos</B></p>
				</div>
				<div class="card-body">

					<?php  
				//CONSULTA DEL MENU (CATEGORIAS)
					$menuacordeon = "SELECT * FROM tbl_menu";

					if ($menuResult = $sqlconnection->query($menuacordeon)) {

						if ($menuResult->num_rows == 0) {
						//echo "<center><label>Sin menús agregados por el momento.</label></center>";
						} ?>
						<!-- MOSTRAR LOS BOTONES DE LAS CATEGORIAS -->
						<div class="accordion" id="accordionExample">
							<?php
							$i=1;
							while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) {?>
								<div class="card">
									<div class="card-header" id="heading<?php echo $i; ?>">
										<h2 class="mb-0">
											<button class="btn btn-info btn-block text-uppercase <?php if($i>1) echo "collapsed"; ?>" type="button" data-toggle="collapse" data-target="#collapse<?php echo $i; ?>" aria-expanded="<?php echo ($i==1)? 'true': 'false'; ?>" aria-controls="collapse<?php echo $i; ?>">
												<?php echo $menuRow["menuName"]; ?>
											</button>
										</h2>
									</div>
									<div id="collapse<?php echo $i; ?>" class="collapse <?php if($i==1) echo 'show'; ?>" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordionExample">
										<div class="card-body">
											<!-- <?php echo $menuRow["menuName"]; ?> -->

											<div class="card mb-3 border-primary">
												<div class="card-header">

													<i class="fas fa-chart-area"></i>
													<?php echo $menuRow["menuName"]; ?>
													<button class="btn btn-danger btn-sm float-right" data-toggle="modal" data-target="#deleteModal" data-category="<?php echo $menuRow["menuName"];?>" data-menuid="<?php echo $menuRow["menuID"];?>">Eliminar</button>

													<button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addItemModal" data-category="<?php echo $menuRow["menuName"];?>" data-menuid="<?php echo $menuRow["menuID"];?>">Agregar</button>

												</div>
												<div class="card-body">

													<table class="display table table-bordered "  width="100%" cellspacing="0">
														<thead>									
															<tr>
																<td>#</td>
																<td>Nombre de Item</td>
																<td>Precio Costo(COP)</td>
																<td>Precio Venta(COP)</td>
																<td>Stock</td>
																<td>Iva</td>
																<td>Estado</td>
																<?php if($_SESSION['user_level'] == "admin"){ ?>
																	<td>Agregar Inventario</td>
																	<td>Editar</td>
																	<td>Eliminar</td>
																	<?php  
																}else{ ?>
																	<td>Agregar Inventario</td>
																<?php  } ?>
															</tr>
														</thead>
														<tbody>
															<?php
															$menuItemQuery = "SELECT * FROM tbl_menuitem WHERE menuID = " . $menuRow["menuID"];

								//No item in menu
															if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {

																if ($menuItemResult->num_rows == 0) {

																}

																$itemno = 1;
									//Fetch and display all item based on their category 
																while($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {	?>

																	<tr>
																		<td><?php echo $itemno++; ?></td>
																		<td><?php echo $menuItemRow["menuItemName"] ?></td>
																		<td><?php echo"$"; echo number_format($menuItemRow["precio_costo"]); ?></td>
																		<td><?php echo"$"; echo number_format($menuItemRow["price"]); ?></td>
																		<td><?php echo $menuItemRow["stock"] ?></td>
																		<td><?php echo $menuItemRow["iva"] ?> %</td>
																		<td><?php echo $menuItemRow["estado"] ?></td>
																		<?php if($_SESSION['user_level'] == "admin"){ ?>
																			<td class="text-center">
																				<a class="btn bg-teal" href="#EntradaInventario" data-toggle="modal" data-itemname="<?php echo $menuItemRow["menuItemName"] ?>" data-itemid="<?php echo $menuItemRow["itemID"] ?>"
																					data-id_vendedor="<?php echo $_SESSION['username'] ?>"><i class="fas fa-plus"></i> </a>
																				</td>
																				<td class="text-center">
																					<a class="btn btn-warning" href="#editItemModal" data-toggle="modal" data-itemname="<?php echo $menuItemRow["menuItemName"] ?>" data-itemprice="<?php echo $menuItemRow["price"] ?>" data-precio_costo="<?php echo $menuItemRow["precio_costo"] ?>" data-stock="<?php echo $menuItemRow["stock"] ?>" data-iva="<?php echo $menuItemRow["iva"] ?>" data-menuid="<?php echo $menuRow["menuID"] ?>" data-itemid="<?php echo $menuItemRow["itemID"] ?>"><i class="fas fa-edit"></i> </a>
																				</td>
																				<td class="text-center">
																					<a class="btn btn-danger" href="deleteitem.php?itemID=<?php echo $menuItemRow["itemID"] ?>&menuID=<?php echo $menuRow["menuID"] ?>"> <i class="fas fa-trash"></i></a>
																				</td>
																				<?php  
																			}else{ ?>
																				<td class="text-center">
																				<a class="btn bg-teal" href="#EntradaInventario" data-toggle="modal" data-itemname="<?php echo $menuItemRow["menuItemName"] ?>" data-itemid="<?php echo $menuItemRow["itemID"] ?>"
																					data-id_vendedor="<?php echo $_SESSION['username'] ?>"><i class="fas fa-plus"></i> </a>
																				</td>
																			<?php  } ?>

																		</tr>

																		<?php
																	}
																}

																else {
																	die("Algo malo paso");
																}
																?>
															</tbody>
														</table>
													</div>
												</div>


											</div>
										</div>
									</div>
									<?php
									$i++;
								}
								?>
							</div>
						<?php } ?>


					</div>
				</div>

			</div>
			<!-- /.container-fluid -->



		</div>
		<!-- /.content-wrapper -->

	</div>
	<!-- /#wrapper -->
	<!-- /#NUEVO STOCK AL INVENTARIO -->
	<div class="modal fade" id="EntradaInventario" tabindex="-1" role="dialog" aria-labelledby="addStock" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addStock">Añadir Stock</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="addentrada" method="POST">
						<div class="row">
							<div class="form-group col-md-6">
								<input type="hidden" id="itemID" name="itemID_fk">
								<input type="hidden" id="vendedor" name="vendedor">
								<label class="col-form-label ">Fecha Ingreso</label>
								<input type="date" class="form-control" name="fecha_stock" required>
							</div>
							<div class="form-group col-md-6">
								<label class="col-form-label">Cantidad </label>
								<input type="number"  class="form-control" name="cantidad_stock" placeholder="Ingrese la cantidad del producto" required>
							</div>
							<div class="form-group col-md-6">
								<label class="col-form-label">Precio Compra</label>
								<input type="number"  class="form-control" name="precio_compra" placeholder="$2000" >
							</div>
							<div class="form-group col-md-6">
								<label class="col-form-label">Precio Venta</label>
								<input type="number"  class="form-control" name="precio_venta" placeholder="$5000" >
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					<button type="submit" form="addentrada" class="btn btn-success" name="addentrada">Agregar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Add Menu Modal -->
	<div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addMenuModalLabel">Agregar Categoría</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="addmenuform" method="POST">
						<div class="form-group">
							<label class="col-form-label">Categoría:</label>
							<input type="text" required="required" class="form-control" name="menuname" placeholder="Bebidas, etc...." >
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					<button type="submit" form="addmenuform" class="btn btn-success" name="addmenu">Agregar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Add Item Modal -->
	<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addItemModalLabel">Agregar Menu</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="additemform" method="POST">
						<div class="form-group">
							<label class="col-form-label">Nombre:</label>
							<input type="text" required="required" class="form-control" name="itemName" placeholder="Nombre del producto" >
						</div>
						<div class="form-group">
							<label class="col-form-label">Precio Costo (COP):</label>
							<input type="number" required="required" class="form-control" name="precio_costo" placeholder="Valor neto del producto"  >
						</div>
						<div class="form-group">
							<label class="col-form-label">Precio Venta (COP):</label>
							<input type="number" required="required" class="form-control" name="itemPrice"  placeholder="Valor de venta">
							<input type="hidden" name="menuID" id="menuid">
						</div>
						<div class="row">
							<div class="col-md-6">
								<label class="col-form-label">Stock:</label>
								<input type="number" required="required" class="form-control" name="stock" placeholder="Cantidad de productos" >
							</div>
							<div class="col-md-6">
								<label class="col-form-label">Iva:</label>
								<input type="number" required="required" class="form-control" name="iva" placeholder="Ingrese Iva" value="0" >
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					<button type="submit" form="additemform" class="btn btn-success" name="addItem">Agregar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Edit Item Modal -->
	<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addItemModalLabel">Editar Menú</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="edititemform" action="" method="POST">
						<div class="form-group">
							<label class="col-form-label">Nombre:</label>
							<input type="text" required="required" id="itemname" class="form-control" name="itemName" placeholder="Sopa,Pepsi,etc" >
						</div>
						<div class="form-group">
							<label class="col-form-label">Precio Costo (COP):</label>
							<input type="text" required="required" id="precio_costo" class="form-control" name="precio_costo" placeholder="Valor neto del producto" >

						</div>
						<div class="form-group">
							<label class="col-form-label">Precio (COP):</label>
							<input type="text" required="required" id="itemprice" class="form-control" name="itemPrice" placeholder="" >
							<input type="hidden" name="menuID" id="menuid">
							<input type="hidden" name="itemID" id="itemid">
						</div>
						<div class="row">
							<div class="col-md-6">
								<label class="col-form-label">Stock:</label>
								<input type="number" required="required" class="form-control" name="stock" id="stock" placeholder="Cantidad de productos" >
							</div>
							<div class="col-md-6">
								<label class="col-form-label">Iva:</label>
								<input type="number" required="required" class="form-control" name="iva" id="iva" placeholder="Ingrese Iva" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Estado:</label>
							<select name="itemEstado" id="itemEstado" class="form-control">
								<option value="Activo">Activo</option>
								<option value="Inactivo">Inactivo</option>
							</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="submit" form="edititemform" class="btn btn-primary" name="btnedit">Editar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Delete Modal-->
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteModalLabel">Estás seguro de eliminar este menú?</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">Seleccione "Eliminar" a continuación se eliminará <strong>todos</strong> su artículo o menú en esta categoría.</div>
				<div class="modal-footer">
					<form id="deletemenuform" method="POST">
						<input type="hidden" name="menuID" id="menuid">
					</form>
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
					<button type="submit" form="deletemenuform" class="btn btn-danger" name="deletemenu">Eliminar</button>
				</div>
			</div>
		</div>
	</div>
	<?php include ('includes/adminfooter.php');?>

	<script>
	    	//MODAL DE AGRAGAR STOCK
	    	$('#EntradaInventario').on('show.bs.modal', function (event) {
			  var button = $(event.relatedTarget); // Button that triggered the modal
			  var itemname = button.data('itemname'); // Extract info from data-* attributes
			  var itemid = button.data('itemid');
			  var id_vendedor = button.data('id_vendedor');

			  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			  var modal = $(this);
			  modal.find('.modal-title').text('Agregar Stock a - ' + itemname );
			  modal.find('.modal-body #itemID').val(itemid);
			  modal.find('.modal-body #vendedor').val(id_vendedor);
			 });
    	//passing menuId to modal
    	$('#addItemModal').on('show.bs.modal', function (event) {
			  var button = $(event.relatedTarget); // Button that triggered the modal
			  var id = button.data('menuid'); // Extract info from data-* attributes
			  var category = button.data('category');

			  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			  var modal = $(this);
			  modal.find('.modal-title').text('Agregar Nuevo Menú -- ' + category );
			  modal.find('.modal-body #menuid').val(id);
			 });

    	$('#editItemModal').on('show.bs.modal', function (event) {
			  var button = $(event.relatedTarget); // Button that triggered the modal

			  var menuid = button.data('menuid'); // Extract info from data-* attributes
			  var itemid = button.data('itemid');
			  var itemname = button.data('itemname');
			  var itemprice = button.data('itemprice');
			  var stock = button.data('stock');
			  var iva = button.data('iva');
			  var precio_costo = button.data('precio_costo');

			  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			  var modal = $(this);
			  modal.find('.modal-body #menuid').val(menuid);
			  modal.find('.modal-body #itemid').val(itemid);
			  modal.find('.modal-body #itemname').val(itemname);
			  modal.find('.modal-body #itemprice').val(itemprice);
			  modal.find('.modal-body #stock').val(stock);
			  modal.find('.modal-body #iva').val(iva);
			  modal.find('.modal-body #precio_costo').val(precio_costo);
			 });


    	$('#deleteModal').on('show.bs.modal', function (event) {
			  var button = $(event.relatedTarget); // Button that triggered the modal
			  var id = button.data('menuid'); // Extract info from data-* attributes
			  var category = button.data('category');

			  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			  var modal = $(this);
			  modal.find('.modal-body').html('Selecciona "Eliminar" y a continuación se borrará esta lista completa');
			  modal.find('.modal-footer #menuid').val(id);
			 });
			</script>

			<script type="text/javascript">
				window.setTimeout(function() {
					$(".alert").fadeTo(500, 0).slideUp(500, function() {
						$(this).hide();
					});
				}, 1000);
			</script> 
