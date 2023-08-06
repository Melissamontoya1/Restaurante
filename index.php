<?php 
include("functions.php");
$id_empresa="1";
$empresacon =  "
SELECT *
FROM empresa WHERE id_empresa='$id_empresa'
";

if ($orderResult = $sqlconnection->query($empresacon)) {
                        //if no order
    if ($orderResult->num_rows == 0) {

        echo "<tr><td class='text-center' colspan='7' >Actualmente no hay empresas registradas. </td></tr>";
    }

    else {
        while($fila2 = $orderResult->fetch_array(MYSQLI_ASSOC)) {
            $id_empresa=$fila2['id_empresa'];
            $nit_empresa=$fila2['nit_empresa'];
            $nombre_empresa=$fila2['nombre_empresa'];
            $direccion_empresa=$fila2['direccion_empresa'];
            $telefono_empresa=$fila2['telefono_empresa'];
            $resolucion=$fila2['resolucion'];
            $logo=$fila2['logo'];
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <!--    TITULO DEL SISTEMA -->
    <title><?php echo $nombre_empresa; ?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
<!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="admin/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="admin/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="admin/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="admin/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="admin/plugins/summernote/summernote-bs4.min.css">

</head>

<body style="background-image: radial-gradient(circle at 50% -20.71%, #e6f58f 0, #e9eb7f 10%, #ecdf6e 20%, #efd25d 30%, #f1c44d 40%, #f2b53c 50%, #f3a52d 60%, #f59522 70%, #f8851d 80%, #fa741e 90%, #fc6324 100%);">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <br>
            <div class="pt-4 col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body pt-5">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class=" col-lg-6 d-none d-lg-block">
                                <?php  
                                echo'
                                <center><img src="admin/img/'.$logo.'" class="img-fluid "></center>
                                ';
                                ?>
                            </div>
                            <div class="col-lg-6">
                                <div class="card p-5 border-bottom-warning">
                                    <div class="text-center">
                                        <h3 class=" text-gray-900 mb-4"><?php echo $nombre_empresa ?></h3>
                                    </div>
                                    <form method="POST" action="login.php" >
                                        <div class="">
                                         <input name="user_name" type="text" class="form-control" placeholder="Usuario" required>
                                         <br>
                                     </div>

                                     <div class="input-group">
                                        <input name="user_password" type="password" class="password1 form-control" placeholder="Contraseña" required>
                                        <br>
                                        <span class="input-group-btn btn btn-success fa fa-fw fa-eye password-icon show-password col-md-2"></span>
                                        

                                    </div>
                                    <div class="pt-5 pb-5" >

                                        <button name="login" class="btn-block btn btn-primary" type="submit">
                                          <i class="fas fa-lg fa-sign-in-alt "></i> Ingresar
                                        </button>
                                    </div>
                                </form>

                                <!-- <div class="text-center">
                                    <a class="small" href="forgot-password.html">Olvidaste tu contraseña?</a>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Bootstrap core JavaScript-->
<script src="admin/js/jquery.min.js"></script>
<script src="admin/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="admin/js/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="admin/js/sb-admin-2.min.js"></script>
<script>
    window.addEventListener("load", function() {

            // icono para mostrar contraseña
            showPassword = document.querySelector('.show-password');
            showPassword.addEventListener('click', () => {

                // elementos input de tipo clave
                password1 = document.querySelector('.password1');

                if ( password1.type === "text" ) {
                    password1.type = "password"
                    showPassword.classList.remove('fa-eye-slash');
                } else {
                    password1.type = "text"
                    showPassword.classList.toggle("fa-eye-slash");
                }

            })

        });

    </script>

</body>

</html>