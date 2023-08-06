<?php
include ('../functions.php');
include ('includes/adminheader.php');
include ('includes/adminnav.php');

$username=$_SESSION['username'];
$consulUser = "SELECT *
FROM tbl_admin
WHERE username='$username'";

if ($result = $sqlconnection->query($consulUser)) {

	if ($result->num_rows > 0) {
		while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
			$username=$fila['username'];
			$password=$fila['password'];
		}
	}else{
		$consulUserE = "SELECT *
		FROM tbl_staff
		WHERE username='$username'";

		if ($result = $sqlconnection->query($consulUserE)) {

			if ($result->num_rows > 0) {
				while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
					$username=$fila['username'];
					$password=$fila['password'];
				}
			}

		}
	}
	
}

if (isset($_POST['update'])) {
	$username=$_SESSION['username'];
	$role=$_SESSION['user_level'];
	$pass1=$_POST['pass1'];
	$pass2=$_POST['pass2'];

	if ($role=="admin") {
		if ($pass1==$pass2) {
			$updateItemQuery = "UPDATE tbl_admin SET password = '{$pass1}'  WHERE username = '{$username}'";

			if ($sqlconnection->query($updateItemQuery) === TRUE) {
				echo '<script>
				swal("Buen Trabajo!", "Se edito con éxito", "success").then(function() {
					window.location.replace("profile.php");
					});

					</script>';
					exit();
				}else {
					echo '<script>swal("ERROR!", "Lo sentimos, no se puedo editar", "error");</script>';
				}
			}else{
				echo '<script>swal("ERROR!", "Lo sentimos, las contraseñas no coinciden", "error");</script>';
			}
		}else{
			if ($pass1==$pass2) {
				$updateUser = "UPDATE tbl_staff SET password = '{$pass1}'  WHERE username = '{$username}'";

				if ($sqlconnection->query($updateUser) === TRUE) {
					echo '<script>
					swal("Buen Trabajo!", "Se edito con éxito", "success").then(function() {
						window.location.replace("profile.php");
						});

						</script>';
						exit();
					}else {
						echo '<script>swal("ERROR!", "Lo sentimos, no se puedo editar", "error");</script>';
					}
				}else{
					echo '<script>swal("ERROR!", "Lo sentimos, las contraseñas no coinciden", "error");</script>';
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
							<h1>Perfil</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
								<li class="breadcrumb-item active">Usuario</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">

							<!-- Profile Image -->
							<div class="card card-primary card-outline">
								<div class="card-body box-profile">
									<div class="text-center">
										<img class="profile-user-img img-fluid img-circle"
										src="img/<?php echo $logotipo; ?>"
										alt="User profile picture">
									</div>

									<h3 class="profile-username text-center text-uppercase"><?php echo $_SESSION['username']; ?></h3>

									<span  class=" btn btn-primary btn-block fa fa-fw fa-eye password-icon show-password "> Mostrar Contraseña</span>
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->


							<!-- /.card -->
						</div>
						<!-- /.col -->
						<div class="col-md-9">
							<div class="card">
								<div class="card-header p-2">
									<ul class="nav nav-pills">

										<li class="nav-item "><a class="nav-link active" href="#settings" data-toggle="tab">Configurar Perfil</a></li>
									</ul>
								</div><!-- /.card-header -->
								<div class="card-body">
									<div class="tab-content">


										<div class="active tab-pane" id="settings">
											<form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
												<div class="form-group row">
													<label for="inputName" class="col-sm-2 col-form-label">Usuario</label>
													<div class="col-sm-10">

														<input type="text" name="username" class="form-control" value="<?php echo $username; ?>" readonly>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputEmail" class="col-sm-2 col-form-label">Contraseña Actual</label>
													<div class="col-sm-10">

														<input type="password" name="" value="<?php echo $password ?>" class="form-control password3" placeholder="Ingrese su actual contraseña" required >
													</div>
												</div>
												<div class="form-group row">
													<label for="usertag" class="col-sm-2 col-form-label">Nueva Contraseña <font color='brown'> (opcional)</font></label>
													<div class="col-sm-10">
														<input type="password" name="pass1" class="form-control password1 " placeholder="Ingrese nueva contraseña" required>

													</div>
												</div>
												<div class="form-group row">
													<label for="inputName2" class="col-sm-2 col-form-label">Confirmar Contraseña </label>
													<div class="col-sm-10">

															<input type="password" name="pass2" class="form-control password2" placeholder="Confirme su nueva contraseña" required >
													</div>
												</div>
											


												<div class="form-group row">
													<div class="offset-sm-2 col-sm-10">
														<button type="submit" class="btn btn-success btn-block" name="update" value="Update User">Guardar Cambios</button>
													</div>
												</div>
											</form>
										</div>
										<!-- /.tab-pane -->
									</div>
									<!-- /.tab-content -->
								</div><!-- /.card-body -->
							</div>
							<!-- /.card -->
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->
		</div>


		<?php include ('includes/adminfooter.php');?> 

		<script>
			window.addEventListener("load", function() {

            // icono para mostrar contraseña
            showPassword = document.querySelector('.show-password');
            showPassword.addEventListener('click', () => {

                // elementos input de tipo clave
                password3 = document.querySelector('.password3');
                password1 = document.querySelector('.password1');
                password2 = document.querySelector('.password2');

                if ( password1.type === "text" ) {
                	password1.type = "password"
                	password2.type = "password"
                	password3.type = "password"
                	showPassword.classList.remove('fa-eye-slash');
                } else {
                	password1.type = "text"
                	password2.type = "text"
                	password3.type = "text"
                	showPassword.classList.toggle("fa-eye-slash");
                }

            })

        });

    </script>







