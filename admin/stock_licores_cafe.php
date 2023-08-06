<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
if (isset($_POST['stock'])) {

	$nombre_preparacion = $sqlconnection->real_escape_string($_POST['nombre_preparacion']);
	$stock_preparacion = $sqlconnection->real_escape_string($_POST['stock_preparacion']);


	$addStaffQuery = "INSERT INTO stock_preparacion (nombre_preparacion,stock_preparacion) VALUES ('{$nombre_preparacion}','{$stock_preparacion}')";

	if ($sqlconnection->query($addStaffQuery) === TRUE) {
		echo '<script>
		swal("Buen Trabajo!", "Se registro con éxito", "success").then(function() {
			window.location.replace("stock_licores_cafe.php");
			});

			</script>';
		} 

		else {
					//handle
			echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error ", "error");</script>';
			echo $sqlconnection->error;
			echo $sqlconnection->error;
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
          <h1>Administrar Inventario de Licores</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Licores e Inventario</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
		<div id="accordion">
			<div class="card">
				<div class="card-header" id="headingOne">
					<h5 class="mb-0">
						<button class="btn btn-primary btn-block text-left" data-toggle="collapse" data-target="#collapseOne"  aria-controls="collapseOne">
							<i class="bi bi-arrow-repeat"></i>
							Agregar Ingredientes
						</button>
					</h5>
				</div>

				<div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
					<div class="card-body">
						<form  id="cicloform" action="" method="POST">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputEmail4">Nombre Ingrediente</label>
									<input type="text" name="nombre_preparacion" class="form-control" placeholder="Escribe el ingrediente EJ: Café ó Ron">
								</div>
								<div class="form-group col-md-6">
									<label for="inputEmail4">Cantidad Gr/Ml</label>
									<input type="text" name="stock_preparacion" class="form-control" placeholder="Cantidad en Gramos o Mililitros EJ 1000">
								</div>

							</div>
							<button type="submit" form="cicloform" name="stock" class="btn btn-success btn-block">Guardar Cambios</button>
						</form>
					</div>
				</div>
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
						<button class="btn btn-success"><i class="fas fa-plus"></i >Agregar Inventario</button>
						</td>
						</tr>';
					}
				}
				?>

			</tbody>
		</table>
	</div>


	<?php include ('includes/adminfooter.php');?>