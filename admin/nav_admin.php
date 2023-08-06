<?php 



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
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $nombre_empresa; ?></title>

  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/fontawesome-free/css/flaticon.css" rel="stylesheet" type="text/css">


  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="index.php">Bienvenido <?php echo $_SESSION['username']; ?></a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
      </li>
    </ul>

  </nav>
 
  <div id="wrapper">

    <!------------------ Sidebar ------------------->
    <ul class="sidebar navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="flaticon-cup-black-silhouette"></i>
          <span>Panel de Control</span>
        </a>
      </li>
      <?php  if ($_SESSION['user_level'] == "admin" ) {
        echo ' 
        <li class="nav-item">
        <a class="nav-link" href="menu.php">
        <i class="flaticon-restaurant"></i>
        <span>Categorias y Productos</span></a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="stock_licores_cafe.php">
        <i class="fas fa-fw fa-lg fa-cocktail"></i>
        <span>Licores y Cafes</span></a>
        </li>';
      }
      ?>
      <li class="nav-item">
        <a class="nav-link" href="mesa.php">
          <i class="flaticon-restaurant-table-and-chairs"></i>
          <span>Mesas</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="sales.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Ventas E Informes</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="gastos.php">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Gastos</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="staff.php">
              <i class="fas fa-fw fa-user-friends"></i>
              <span>Clientes</span>
            </a>
          </li>
          <?php  if ($_SESSION['user_level'] == "admin" ) {
            echo '  
            <li class="nav-item">
            <a class="nav-link" href="empleado.php">
            <i class="flaticon-chef"></i>
            <span>Empleados</span>
            </a>
            </li>
            ';
          }
          ?>
          <li class="nav-item">
            <a class="nav-link" href="staff/kitchen.php">
              <i class="fas fa-fw fa-book"></i>
              <span>Pedidos</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="staff/order.php">
              <i class="flaticon-hand-carrying-a-tea-cup-of-restaurant-service"></i>
              <span>Tomar Orden</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="cierres.php">
              <i class="fas fa-fw fa-key"></i>
              <span>Cierres</span>
            </a>
          </li>
          <?php  if ($_SESSION['user_level'] == "admin" ) {
            echo ' 
            <li class="nav-item">
            <a class="nav-link" href="cuentas.php">
            <i class="fas fa-fw fa-money-check-alt"></i>
            <span>Cuentas</span>
            </a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="empresa.php">
            <i class="fas fa-fw fa-building"></i>
            <span>Empresa</span>
            </a>
            </li> 
            ';
          }
          ?>
          <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
              <i class="fas fa-fw fa-power-off"></i>
              <span>Cerrar Sesión</span>
            </a>
          </li>

        </ul>

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
          <i class="fas fa-angle-up"></i>
        </a>
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Preparado para partir?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión actual.</div>
              <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="logout.php">Cerrar Sesión</a>
              </div>
            </div>
          </div>
        </div>
        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © Sistema de Restaurante Dutec 2021</span>
            </div>
          </div>
        </footer>


        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin.min.js"></script>

        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <script src="js/codigo.js"></script>

