<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');


?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Panel Administrativo de la Cocina</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Cocina</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="container-fluid">



      <div class="card mb-3">
        <div class="card-header">
          <i class="fas fa-utensils"></i>
        Listado de Últimas Órdenes Recibidas</div>
        <div class="card-body table-responsive">
         <table id="tblCurrentOrder" class=" table table-bordered text-center  " width="100%" cellspacing="0">
           <thead class="bg-success text-center">
            <th># / Mesa / Cliente </th>
            <th>Categoría</th>
            <th>Producto</th>
            <th>Cant</th>
            <th>Precio</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Total (COP)</th>
            <th>Opciones</th>
          </thead>

          <tbody id="tblBodyCurrentOrder">

          </tbody>
        </table>
      </div>
    </div>

  </div>
  <!-- /.container-fluid -->
</section>
</div>



<script type="text/javascript">
      if (window.history.replaceState) { // verificamos disponibilidad
        window.history.replaceState(null, null, window.location.href);
      }

      $( document ).ready(function() {
        refreshTableOrder();
      });

      function refreshTableOrder() {
       $( "#tblBodyCurrentOrder" ).load( "displayorder.php?cmd=currentorder" );
     }


     function editStatus (objBtn,orderID) {
       var status = objBtn.value;

       $.ajax({
        url : "editstatus.php",
        type : 'POST',
        data : {
          orderID : orderID,
          status : status 
        },

        success : function(output) {
          refreshTableOrder();
        }
      });
     }

    //refresh order current list every 3 secs
    //setInterval(function(){ refreshTableOrder(); }, 3000);

  </script>
  <?php include ('includes/adminfooter.php');?>