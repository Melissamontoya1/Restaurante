  <?php
  include("../functions.php");
  include('includes/adminheader.php');
  include ('includes/adminnav.php');
  include('editar_cuenta.php');
  ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Información Cuentas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Cuentas</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
  <div class="row">
   <div class="col-md-12">
    <center>
     <!-- Agregar Cliente modal -->
     <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#agregarGasto">
      <span class="icon text-white-50">
        <i class="fas fa-plus"></i>
      </span>
      <span class="text">  Agregar Cuenta </span>
    </button>
  </center>
</div>
</div>
<br>
<div class="row">
 <div class="col-lg-12">
  <div class="card mb-3">
   <div class="card-header bg-navy">
    <i class="fas fa-fw fa-credit-card"></i>
  Lista Actual de Cuentas</div>
  <div class="card-body caja">
    <table class="display table table-bordered text-center "  width="100%" cellspacing="0">
     <thead>
      <th>Cuenta # </th>
      <th>Nombre </th>
      <th>Estado</th>
      <th>Opción</th>
    </thead>
    <tbody id="caja_gastos">
      <?php 
      $displayStaffQuery = "SELECT *
      FROM tipo_caja
      ORDER BY id_caja ASC";

      if ($result = $sqlconnection->query($displayStaffQuery)) {

       if ($result->num_rows == 0) {
        echo "<td colspan='4'>No hay cuentas registradas.</td>";
      }
      while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
        $id_caja=$fila['id_caja'];
        $nombre_caja=$fila['nombre_caja'];
        ?>  
        <tr> 

         <?php if ($nombre_caja=="Efectivo"){?>
          <td><?php echo $fila['id_caja']; ?></td>
          <td><?php echo $fila['nombre_caja']; ?></td>
          <td><?php echo $fila['estado_caja']; ?></td>
          <td>No se puede editar</td>
          <?php  
        }else{?>
          <td><?php echo $fila['id_caja']; ?></td>
          <td><?php echo $fila['nombre_caja']; ?></td>
          <td><?php echo $fila['estado_caja']; ?></td>
          <td>
            <a class="btn btn-sm btn-warning" href="#editItemModalMesa" data-toggle="modal" data-nombre_caja="<?php echo $fila["nombre_caja"] ?>" data-estado_caja="<?php echo $fila["estado_caja"] ?>"   data-id_caja="<?php echo $fila["id_caja"] ?>" ><i class="fas fa-edit"></i></a>

          </td>
          <?php  
        } ?>



      </tr>
      <?php 
    }

  }
  else {
   echo $sqlconnection->error;
   echo "ERROR.";
 }

 ?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>


<!-- Modal AGREGAR CLIENTE-->
<div class="modal fade" id="agregarGasto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
   <div class="modal-content">
    <div class="modal-header">
     <h4 class="modal-title" id="myModalLabel">Agregar Nueva Cuenta</h4>
     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   </div>
   <div class="modal-body">
     <form id="agregar_cuenta" action="agregar_cuenta.php" method="POST">
      <div class="form-group ">
       <label class="col-form-label">Nombre Banco:</label>
       <input type="text" required="required" id="nombre_caja" class="form-control" name="nombre_caja"  >
     </div>
     <div class="form-group ">
       <label class="col-form-label">Estado</label>
       <select name="estado_caja" class="form-control" id="estado_caja">
        <option value="Activo">Activo</option>
        <option value="Inactivo">Inactivo</option>
      </select>

    </div>
  </form>
</div>
<div class="modal-footer">
 <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
 <button type="submit" form="agregar_cuenta" class="btn btn-success" name="agregar_cuenta">Enviar</button>
</div>
</div>
</div>
</div> <!-- CIERRE MODAL -->


<div class="modal fade" id="editItemModalMesa" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
   <div class="modal-content">
    <div class="modal-header">
     <h5 class="modal-title" id="addItemModalLabel">Editar Cuenta</h5>
     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
   <form id="editar_cuenta" action="" method="POST" class="">

    <div class="form-group ">
     <label class="col-form-label">Nombre Banco:</label>
     <input type="text" required="required" id="nombre_caja" class="form-control" name="nombre_caja"  >
   </div>
   <div class="form-group ">
     <label class="col-form-label">Estado :</label>
     <select name="estado_caja" class="form-control" id="estado_caja">
      <option value="Activo">Activo</option>
      <option value="Inactivo">Inactivo</option>
    </select>
    <input type="hidden" required="required" id="id_caja" class="form-control" name="id_caja" >
  </div>

</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
  <button type="submit" form="editar_cuenta" class="btn btn-success" name="editar_cuenta">Editar</button>
</div>
</div>
</section>
</div>
<?php include ('includes/adminfooter.php');?>
<script>
 $('#editItemModalMesa').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget); // Button that triggered the modal

          var id_caja = button.data('id_caja'); // Extract info from data-* attributes
          var nombre_caja = button.data('nombre_caja');
          var estado_caja = button.data('estado_caja');
          var valor_gasto = button.data('valor_gasto');
          

          // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
          var modal = $(this);
          modal.find('.modal-body #id_caja').val(id_caja);
          modal.find('.modal-body #nombre_caja').val(nombre_caja);
          modal.find('.modal-body #estado_caja').val(estado_caja);


        });
      </script>

