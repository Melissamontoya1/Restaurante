<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
include('agregar_cliente.php');
include('edit_cliente.php');
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Administrar Clientes</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Clientes</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
    <div class="row">

      <div class="col-md-8">
        <center>

          <!-- Agregar Cliente modal -->
          <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#myModalagregar">
           <i class="fas fa-user-circle"></i>
           Agregar Cliente
         </button>
       </center>
     </div>
     
     <div class="col-md-4">
      <center>
        <!-- Agregar Cliente modal -->
        
        <button type="button" class="btn btn-success " data-toggle="modal" data-target="#myModaltipo">
          <i class="fa fa-plus-square"></i> 
          &nbsp;&nbsp;Agregar Tipo
        </button>
      </center>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-lg-12">
      <div class="card mb-3">
        <div class="card-header bg-navy">
          <i class="fas fa-user-circle"></i>
        Lista Actual de Clientes</div>
        <div class="card-body caja">
          <table class=" display table table-bordered text-center " width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Identificación</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Opción</th>

              </tr>
            </thead>
            <tbody>
              <?php 


              $displayStaffQuery = "SELECT c.id_cliente,c.nombres,c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,t.id_tipo_cliente,t.descripcion
              FROM cliente c
              INNER JOIN tipo_cliente t
              ON c.id_tipo_cliente = t.id_tipo_cliente ORDER BY c.id_cliente ASC";

              if ($result = $sqlconnection->query($displayStaffQuery)) {

                if ($result->num_rows == 0) {
                  echo "<td colspan='4'>No hay clientes registrados.</td>";
                }


                while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
                  if ($fila['identificacion']=="000") {
                  // Ocultar esta identificacion para evitar errores
                  }else{
                    ?>  
                    <tr>
                      <td><?php echo $fila['identificacion']; ?></td>
                      <td><?php echo $fila['descripcion']; ?></td>
                      <td><?php echo $fila['nombres']; ?></td>
                      <td><?php echo $fila['direccion']; ?></td>
                      <td><?php echo $fila['telefono']; ?></td>
                      <td><?php echo $fila['correo']; ?></td>

                      <td>
                        <a class="btn btn-sm btn-warning" href="#editItemModal" data-toggle="modal" data-nombres="<?php echo $fila["nombres"] ?>" data-identificacion="<?php echo $fila["identificacion"] ?>" data-tipo="<?php echo $fila["tipo_cliente"] ?>" data-direccion="<?php echo $fila["direccion"] ?>" data-telefono="<?php echo $fila["telefono"] ?>" data-correo="<?php echo $fila["correo"] ?>" data-id_cliente="<?php echo $fila["id_cliente"] ?>" ><i class="fas fa-edit"></i></a>
                        <hr>
                        <a href="deletestaff.php?id_cliente=<?php echo $fila['id_cliente']; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                      </td>

                    </tr>

                    <?php 
                  }

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
<div class="modal fade" id="myModalagregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title" id="myModalLabel">Agregar Cliente</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="agregarcliente" action="" method="POST" class="form-inline">
          <div class="form-group col-md-6">
            <label class="col-form-label">Identificación:</label>
            <input type="text" required="required" id="identificacion" class="form-control" name="identificacion" placeholder="CC / NIT" >
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Nombre:</label>
            <input type="text" required="required" id="nombres" class="form-control" name="nombres" placeholder="Nombre Cliente" >
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Tipo de cliente:</label>
            <select class="form-control id_tipo_cliente" name="id_tipo_cliente">
              <option value="1" selected>Persona Natural</option>
              <?php
              $sql_vendedor=mysqli_query($sqlconnection,"select * from tipo_cliente");
              while ($rw=mysqli_fetch_array($sql_vendedor)){
                $id_tipo_cliente=$rw["id_tipo_cliente"];
                $descripcion=$rw["descripcion"];

                ?>

                <option value="<?php echo $id_tipo_cliente?>" name="id_tipo_cliente" class="id_tipo_cliente"><?php echo $id_tipo_cliente." - ".$descripcion?></option>
                <?php
              }
              ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Dirección:</label>
            <input type="text" required="required" id="direccion" class="form-control" name="direccion" placeholder="Direccion Cliente" >
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Teléfono:</label>
            <input type="text" required="required" id="telefono" class="form-control" name="telefono" placeholder="Telefono Cliente" >
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Correo:</label>
            <input type="text"  id="correo" class="form-control" name="correo" placeholder="Correo Cliente" >
            <input type="hidden"  id="id_cliente" class="form-control" name="id_cliente" >
          </div>
        </form>
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
       <button type="submit" form="agregarcliente" class="btn btn-success" name="btnagregarc">Enviar</button>
     </div>
   </div>
 </div>
</div>

<!-- Modal AGREGAR TIPO CLIENTE-->
<div class="modal fade" id="myModaltipo" tabindex="-1" role="dialog" aria-labelledby="myModaltipo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title" id="myModalLabel">Tipos de cliente</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="addtipo" action="addtipo.php" method="POST" class="form-inline">

          <div class="form-group col-md-6">
            <label class="col-form-label">Tipos de cliente existentes:</label>
            <select class="form-control id_tipo_cliente" name="id_tipo_cliente">
              <option value="1" selected>Persona Natural</option>
              <?php
              $sql_vendedor=mysqli_query($sqlconnection,"select * from tipo_cliente");
              while ($rw=mysqli_fetch_array($sql_vendedor)){
                $id_tipo_cliente=$rw["id_tipo_cliente"];
                $descripcion=$rw["descripcion"];

                ?>

                <option value="<?php echo $id_tipo_cliente?>" name="id_tipo_cliente" class="id_tipo_cliente" <?php echo $selected;?>><?php echo $id_tipo_cliente." - ".$descripcion?></option>
                <?php
              }
              ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Nuevo Tipo</label>
            <input type="text" required="required" id="tipo" class="form-control" name="tipo" placeholder="Natural, Juridica etc." >
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="submit" form="addtipo" class="btn btn-success" name="tipocliente">Enviar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addItemModalLabel">Editar datos del cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editcliente" action="" method="POST" class="form-inline">
          <div class="form-group col-md-6">
            <label class="col-form-label">Identificacion:</label>
            <input type="text" required="required" id="identificacion" class="form-control" name="identificacion" placeholder="CC / NIT" >
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Nombre:</label>
            <input type="text" required="required" id="nombres" class="form-control" name="nombres" placeholder="Nombre Cliente" >
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Tipo de cliente:</label>
            <select class="form-control id_tipo_cliente" name="id_tipo_cliente">
              <option value="1" selected>Persona Natural</option>
              <?php
              $sql_vendedor=mysqli_query($sqlconnection,"select * from tipo_cliente");
              while ($rw=mysqli_fetch_array($sql_vendedor)){
                $id_tipo_cliente=$rw["id_tipo_cliente"];
                $descripcion=$rw["descripcion"];

                ?>

                <option value="<?php echo $id_tipo_cliente?>" name="id_tipo_cliente" class="id_tipo_cliente" <?php echo $selected;?>><?php echo $id_tipo_cliente." - ".$descripcion?></option>
                <?php
              }
              ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Direccion:</label>
            <input type="text" required="required" id="direccion" class="form-control" name="direccion" placeholder="Direccion Cliente" >
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Telefono:</label>
            <input type="text" required="required" id="telefono" class="form-control" name="telefono" placeholder="Telefono Cliente" >
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Correo:</label>
            <input type="text" required="required" id="correo" class="form-control" name="correo" placeholder="Correo Cliente" >
            <input type="hidden" required="required" id="id_cliente" class="form-control" name="id_cliente" >
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="submit" form="editcliente" class="btn btn-success" name="btneditc">Editar</button>
      </div>
    </div>
  </div>
</div>






</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- FOOTER ANTES DE LOS SCRIPT -->

<?php include ('includes/adminfooter.php');?>

<script>
  $('#editItemModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        var identificacion = button.data('identificacion'); // Extract info from data-* attributes
        var direccion = button.data('direccion');
        var nombres = button.data('nombres');
        var correo = button.data('correo');
        var telefono = button.data('telefono');
        var id_cliente = button.data('id_cliente');

        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-body #identificacion').val(identificacion);
        modal.find('.modal-body #direccion').val(direccion);
        modal.find('.modal-body #nombres').val(nombres);
        modal.find('.modal-body #correo').val(correo);
        modal.find('.modal-body #telefono').val(telefono);
        modal.find('.modal-body #id_cliente').val(id_cliente);
      });
    </script>
