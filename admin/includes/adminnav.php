<?php 
include("empresa_datos.php");
//session_start();

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="order.php" class="nav-link">Tomar Orden</a>
        </li>
        <?php 
        if ($_SESSION['user_level'] == "admin" && $tipo_negocio=="Restaurante") {?>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="kitchen.php" class="nav-link">Cocina</a>
            </li>
        <?php } ?>
        <?php  
        if ($_SESSION['user_level'] == "admin" && $tipo_negocio=="Restaurante" && $pago_directo=="Si") {?>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="mesa.php" class="nav-link">Mesas</a>
            </li>
        <?php } ?>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="staff.php" class="nav-link">Clientes</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
   <!--      <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li> -->


        <!-- Notifications Dropdown Menu -->
<!--         <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li> -->
        <li class="nav-item">
            <a class="dropdown-item" href="../logout.php" data-toggle="modal" data-target="#logoutModal">
              <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
              Logout
          </a>

      </li>
      <li class="nav-item">
       <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
    </a>
</li>
<li class="nav-item">
 <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
  <i class="fas fa-th-large"></i>
</a>
</li>
</ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
 <!-- Brand Logo -->
 <a href="index.php" class="brand-link">

  <span class="brand-text font-weight-light" style="font-size :15px;"><?php echo $nombre_empresa; ?></span>
</a>

<!-- Sidebar -->
<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
   <div class="image">
    <img src="img/<?php echo $logotipo; ?>" class="img-circle elevation-2" alt="User Image">
</div>
<div class="info">
    <a href="./profile.php?section=<?php echo $_SESSION['username']; ?>" class="d-block text-uppercase"><?php echo $_SESSION['username']; ?></a>
</div>
</div>
<?php 
if ($_SESSION['user_level'] == "admin" ) {?>
    <!-- SidebarSearch Form -->
    <div class="form-inline">
     <div class="input-group" data-widget="sidebar-search">
      <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Search">
      <div class="input-group-append">
       <button class="btn btn-sidebar">
        <i class="fas fa-search fa-fw"></i>
    </button>
</div>
</div>
</div>
<?php } ?>

<!-- Sidebar Menu -->
<nav class="mt-2">
 <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->

            <li class="nav-item">
                <a href="index.php" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Panel de Control</p>
                </a>
            </li> 
            <?php if ($tipo_negocio=="Restaurante"){ ?>
                <li class="nav-item">
                <a href="creditos.php" class="nav-link">
                  <i class="nav-icon far fa-circle text-primary"></i>
                  <p>Creditos</p>
              </a>
          </li>
           
            
          <li class="nav-item">
            <a href="propinas.php" class="nav-link">
              <i class="nav-icon far fa-circle text-success"></i>
              <p>Propinas</p>
          </a>
      </li>
       <?php } ?>
<!--             <li class="nav-item">
              <a href="iframe.php" class="nav-link">
                <i class="nav-icon fas fa-ellipsis-h"></i>
                <p>Zona de Trabajo</p>
              </a>
          </li> -->
          <?php 
          if ($_SESSION['user_level'] == "admin" && $tipo_negocio=="Restaurante" && $pago_directo=="No" || $_SESSION['user_level'] == "staff") {?>
              <li class="nav-item">
                  <a href="kitchen.php" class="nav-link">
                     <i class="fas fa-concierge-bell nav-icon"></i>
                     <p>Pedidos</p>
                 </a>
             </li> 
         <?php } ?>
         <li class="nav-item">
          <a href="order.php" class="nav-link">
             <i class="fas fa-utensils nav-icon"></i>
             <p>Tomar Orden</p>
         </a>
     </li> 

     <!-- Menu para administrar Usuarios-->
     <?php 
     if ($_SESSION['user_level'] == "admin" || $_SESSION['user_level'] == "staff") {?>
         <li class="nav-item">
          <a href="#" class="nav-link">
           <i class="fas fa-cogs nav-icon"></i>
           <p>  Menu e Inventario
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">

     <li class="nav-item">
        <a class="nav-link" href="./menu.php">
            <i class="far fa-circle nav-icon"></i>
            <p> Menú</p>
        </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./entradas_stock.php">
         <i class="far fa-circle nav-icon"></i>
         <p>Ingresos Stock</p>
     </a>
 </li>
 <?php if ($inventario_licores=="si") { ?>
   <li class="nav-item">
    <a href="./stock_licores_cafe.php" class="nav-link">
     <i class="far fa-circle nav-icon"></i>
     <p> Inventario Licores + </p>
 </a>
</li>
<li class="nav-item">
  <a href="./asociar_licores_cafe.php" class="nav-link">
   <i class="far fa-circle nav-icon"></i>
   <p> Asociar Licores +</p>
</a>
</li>
<?php } ?>

</ul>
</li>
<?php } ?>
<?php 
if ($_SESSION['user_level'] == "admin" || $_SESSION['user_level'] == "staff") {?>
    <!-- MENU EVALUACIONES -->
    <li class="nav-item">
      <a href="#" class="nav-link">
       <i class="fas fa-dollar-sign nav-icon" ></i>
       <p> Finanzas
        <i class="right fas fa-angle-left"></i>
    </p>
</a>
<ul class="nav nav-treeview">
    <li class="nav-item">
        <a class="nav-link" href="./sales.php">
           <i class="far fa-circle nav-icon"></i>
           <p> Ventas E Informes</p>
       </a>
   </li>

   <?php if ($caja_general=="si"){ ?>
     <li class="nav-item">
      <a class="nav-link" href="./caja_general.php">
       <i class="far fa-circle nav-icon"></i>
       <p> Caja General</p>
   </a>
</li>
<?php } ?>

<li class="nav-item">
    <a class="nav-link" href="./gastos.php">
       <i class="far fa-circle nav-icon"></i>
       <p> Gastos</p>
   </a>
</li>
<li class="nav-item">
  <a href="cierres.php" class="nav-link">
   <i class="far fa-circle nav-icon"></i>
   <p>Cierres</p>
</a>
</li>

</ul>
</li>

<?php } ?>


<!-- MENU EMPRESA -->
<?php 
if ($_SESSION['user_level'] == "admin" ) {?>

    <li class="nav-item">
        <a class="nav-link" href="./empresa.php">
         <i class="fas fa-city  nav-icon"></i>
         <p> Configurar Empresa</p>
     </a>
 </li>
 <!-- MENU BIBLIOTECA -->
 <li class="nav-item">
  <a href="#" class="nav-link">
   <i class="fas fa-archive nav-icon"></i>
   <p> Configuración
    <i class="right fas fa-angle-left"></i>
</p>
</a>

<ul class="nav nav-treeview">
    <?php  
    if ( $tipo_negocio=="Restaurante") {?>
        <li class="nav-item">
          <a href="./mesa.php" class="nav-link">
           <i class="far fa-circle nav-icon"></i>
           <p> Administrar Mesas</p>
       </a>
   </li>
<?php } ?>
<li class="nav-item">
  <a href="./staff.php" class="nav-link">
   <i class="far fa-circle nav-icon"></i>
   <p> Administrar Clientes</p>
</a>
</li>
<?php 
if ($_SESSION['user_level'] == "admin" ) {?>
   <li class="nav-item">
    <a class="nav-link" href="./empleado.php">
     <i class="far fa-circle nav-icon"></i>
     <p> Empleados</p>
 </a>
</li>
<?php } ?>
<li class="nav-item">
  <a href="./cuentas.php" class="nav-link">
   <i class="far fa-circle nav-icon"></i>
   <p> Administrar Cuentas</p>
</a>
</li>
</ul>
</li>
<?php } ?>


<!-- CIERRE ETIQUETAS DEL NAV -->
</ul>
</nav>
<!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
