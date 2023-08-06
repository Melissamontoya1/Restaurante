<?php 
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
include ('empresa_datos.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Información Del Cierre</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Caja Cerrada</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
               
                <div class="col-md-12">
                    <p>El correo de envio con exito <B><?php echo $_SESSION['username']; ?></B>, por favor adjuntar los reportes al correo que se acabo de enviar por medio de correo  <B> <?php echo $correo_empresa; ?> </B></p>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-wrapper -->

    </section>
    </div>
<?php include ('includes/adminfooter.php');?>