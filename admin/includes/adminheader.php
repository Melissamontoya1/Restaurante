<?php
include('connection.php');
//session_start();
if (isset($_SESSION['username'])) {

}
else {
  echo "<script>alert('Necesitas ingresar primero');
  window.location.href='../index.php';</script>";  
}

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
      $caja_general=$fila2['caja_general'];
      $prefijo=$fila2['prefijo'];
      $inventario_licores=$fila2['inventario_licores'];
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
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="plugins/fullcalendar/main.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="plugins/ekko-lightbox/ekko-lightbox.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <style>


  </style>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Preloader -->
  <!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="img/<?php echo $logo; ?>" alt="AdminLTELogo" height="180" width="180">
  </div> -->
  