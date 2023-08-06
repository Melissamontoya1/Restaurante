  <?php
  include("../functions.php");
  include('includes/adminheader.php');
  include ('includes/adminnav.php');
  include("eliminar_mesa.php");
  include("agregar_mesa.php");
  include("editar_mesa.php");
  ?>



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Administrar Mesas</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Mesas</li>
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
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Mesas</a></li>
                  <li class="nav-item"><a class="nav-link" href="#tipo" data-toggle="tab">Tipo de Mesa / Asientos</a></li>
                  <li class="nav-item"><a class="nav-link" href="#area" data-toggle="tab">Configurar Area</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                   <button type="button" class="btn btn-primary btn-icon-split  " data-toggle="modal" data-target="#agregarmesa">
                    <span class="icon text-white-50">
                      <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Agregar Mesa      </span>
                  </button>
                  <div class="card-header">
                    <i class="flaticon-restaurant-table-and-chairs"></i>
                    Lista Actual de Mesas
                  </div>
                  <div class="card-body ">
                    <table class="display table table-bordered text-center " width="100%" cellspacing="0">
                      <thead>
                       <th>Tipo</th>
                       <th>Area</th>
                       <th>Numero Mesa </th>
                       <th>Estado</th>
                       <th>Editar</th>
                       <th>Eliminar</th>
                     </thead>
                     <tbody >
                      <?php 
                      $displayStaffQuery = "SELECT m.id_mesa,m.numero_mesa,m.estado_mesa,m.id_area_fk,m.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
                      FROM mesa m
                      INNER JOIN area_mesa a
                      ON m.id_area_fk=a.id_area
                      INNER JOIN tipo_mesa t 
                      ON m.id_tipo_fk= t.id_tipo ";

                      if ($result = $sqlconnection->query($displayStaffQuery)) {

                        if ($result->num_rows == 0) {
                          echo "<td colspan='4'>No hay mesas registradas.</td>";
                        }
                        while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
                          $id_mesa=$fila['id_mesa'];
                          ?>  
                          <tr>
                           <td><?php echo $fila['nombre_tipo']; ?></td>
                           <td><?php echo $fila['nombre_area']; ?></td>
                           <td><?php echo $fila['numero_mesa']; ?></td>
                           <td><?php echo $fila['estado_mesa']; ?></td>
                           <td>
                            <a class="btn  btn-warning" href="#editItemModalMesa" data-toggle="modal" data-tipo_mesa="<?php echo $fila["id_tipo_fk"] ?>" data-numero_mesa="<?php echo $fila["numero_mesa"] ?>" data-estado_mesa="<?php echo $fila["estado_mesa"] ?>"  data-id_mesa="<?php echo $fila["id_mesa"] ?>" ><i class="fas fa-edit"></i></a>
                          </td>
                          <td>

                            <button class="btn btn-danger" data-toggle="modal" data-target="#eliminarMesa"  data-id_mesa="<?php echo $fila["id_mesa"];?>"><i class="fas fa-trash"></i></button>
                          </td>
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
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tipo">
              <!-- The timeline -->
              <button type="button" class="btn btn-primary btn-icon-split  " data-toggle="modal" data-target="#agregartipo">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">Nuevo tipo de Mesa     </span>
              </button>
              <div class="card-header">
                <i class="flaticon-restaurant-table-and-chairs"></i>
                Lista Actual de Mesas
              </div>
              <div class="card-body ">
                <table class="display table table-bordered text-center " width="100%" cellspacing="0">
                  <thead>
                   <th>#</th>
                   <th>Nombre</th>
                   <th>Editar</th>
                   <th>Eliminar</th>
                 </thead>
                 <tbody >
                  <?php 
                  $displayStaffQuery = "SELECT *
                  FROM tipo_mesa 
                  ORDER BY id_tipo DESC ";

                  if ($result = $sqlconnection->query($displayStaffQuery)) {

                    if ($result->num_rows == 0) {
                      echo "<td colspan='4'>No hay mesas registradas.</td>";
                    }
                    while($fila = $result->fetch_array(MYSQLI_ASSOC)) {

                      ?>  
                      <tr>
                       <td><?php echo $fila['id_tipo']; ?></td>
                       <td><?php echo $fila['nombre_tipo']; ?></td>

                       <td>
                        <a class="btn  btn-warning" href="#editItemModalMesa" data-toggle="modal"  data-nombre_tipo="<?php echo $fila["nombre_tipo"] ?>"  data-id_tipo="<?php echo $fila["id_tipo"] ?>" ><i class="fas fa-edit"></i></a>
                      </td>
                      <td>

                        <button class="btn btn-danger" data-toggle="modal" data-target="#eliminarMesa"  data-id_tipo="<?php echo $fila["id_tipo"];?>"><i class="fas fa-trash"></i></button>
                      </td>
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
        <!-- /.tab-pane -->

        <div class="tab-pane" id="area">
         <button type="button" class="btn btn-primary btn-icon-split  " data-toggle="modal" data-target="#agregararea">
          <span class="icon text-white-50">
            <i class="fas fa-plus"></i>
          </span>
          <span class="text">Nueva Area</span>
        </button>
        <div class="card-header">
          <i class="flaticon-restaurant-table-and-chairs"></i>
          Lista Actual de Mesas
        </div>
        <div class="card-body ">
          <table class="display table table-bordered text-center " width="100%" cellspacing="0">
            <thead>
             <th>#</th>
             <th>Nombre</th>
             <th>Editar</th>
             <th>Eliminar</th>
           </thead>
           <tbody >
            <?php 
            $displayStaffQuery = "SELECT *
            FROM area_mesa 
            ORDER BY id_area DESC ";

            if ($result = $sqlconnection->query($displayStaffQuery)) {

              if ($result->num_rows == 0) {
                echo "<td colspan='4'>No hay mesas registradas.</td>";
              }
              while($fila = $result->fetch_array(MYSQLI_ASSOC)) {

                ?>  
                <tr>
                 <td><?php echo $fila['id_area']; ?></td>
                 <td><?php echo $fila['nombre_area']; ?></td>

                 <td>
                  <a class="btn  btn-warning" href="#editItemModalMesa" data-toggle="modal"   data-nombre_area="<?php echo $fila["nombre_area"] ?>"  data-id_area="<?php echo $fila["id_area"] ?>" ><i class="fas fa-edit"></i></a>
                </td>
                <td>

                  <button class="btn btn-danger" data-toggle="modal" data-target="#eliminarMesa"  data-id_area="<?php echo $fila["id_area"];?>"><i class="fas fa-trash"></i></button>
                </td>
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
  <!-- /.tab-pane -->
</div>
<!-- /.tab-content -->
</div><!-- /.card-body -->
</div>
<!-- /.card -->
</div>

</div>
</section>
</div>

<!-- Modal AGREGAR CLIENTE-->
<div class="modal fade" id="agregarmesa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Agregar Mesa</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="agregar_mesa" action="" method="POST" class="form-inline">
          <div class="form-group col-md-6">
            <label class="col-form-label">Area Mesa:</label>
            <select class="form-control input-sm id_area_fk" name="id_area_fk" required>
              <option > Seleccione una opcion</option>
              <?php
              $sql_vendedor=mysqli_query($sqlconnection,"select * from area_mesa order by id_area desc");
              while ($rw=mysqli_fetch_array($sql_vendedor)){
                $id_area=$rw["id_area"];
                $nombre_area=$rw["nombre_area"];


                ?>

                <option value="<?php echo $id_area?>" name="id_area_fk" class="id_area">
                  <?php echo $nombre_area ?>
                </option>
                <?php
              }
              ?>
            </select>
          </div>
            <div class="form-group col-md-6">
            <label class="col-form-label">Tipo de Mesa:</label>
            <select class="form-control input-sm id_tipo_fk" name="id_tipo_fk" required>
              <option > Seleccione una opcion</option>
              <?php
              $sql_vendedor=mysqli_query($sqlconnection,"select * from tipo_mesa order by id_tipo desc");
              while ($rw=mysqli_fetch_array($sql_vendedor)){
                $id_tipo=$rw["id_tipo"];
                $nombre_tipo=$rw["nombre_tipo"];


                ?>

                <option value="<?php echo $id_tipo?>" name="id_tipo_fk" class="id_tipo">
                  <?php echo $nombre_tipo ?>
                </option>
                <?php
              }
              ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Numero:</label>
            <input type="text" required="required" id="numero_mesa" class="form-control" name="numero_mesa" placeholder="Numero Mesa" >
          </div>
        
          <div class="col-md-6 form-group">
            <label class="col-form-label">Estado :</label>
            <select class="form-control" name="estado_mesa" id="">
             <option value="Habilitada">Habilitada</option>
             <option value="Deshabilitada">Deshabilitada</option>
           </select>
         </div>
       </form>
     </div>
     <div class="modal-footer">
       <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
       <button type="submit" form="agregar_mesa" class="btn btn-success" name="agregar_mesa">Enviar</button>
     </div>
   </div>
 </div>
</div>
<!-- Modal AGREGAR NUEVO TIPO DE MESA-->
<div class="modal fade" id="agregartipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Nuevo tipo Mesa/Barra/Silla :</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="agregar_tipo" action="" method="POST" >
          <input type="text" required="required" id="nombre_tipo" class="form-control" name="nombre_tipo" placeholder="Nombre Ej: Mesa / Barra / Silla/ " >
        </form>

        <div class="modal-footer">
         <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
         <button type="submit" form="agregar_tipo" class="btn btn-success" name="agregar_tipo">Enviar</button>
       </div>
     </div>
   </div>
 </div>
</div>
<!-- Modal AGREGAR NUEVA AREA-->
<div class="modal fade" id="agregararea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Nueva Area de Distribucion:</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="agregar_area" action="" method="POST" >
          <input type="text" required="required" id="nombre_area" class="form-control" name="nombre_area" placeholder="Nombre del Area" >
        </form>

        <div class="modal-footer">
         <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
         <button type="submit" form="agregar_area" class="btn btn-success" name="agregar_area">Enviar</button>
       </div>
     </div>
   </div>
 </div>
</div>
<!-- EDITAR MESA -->
<div class="modal fade" id="editItemModalMesa" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addItemModalLabel">Editar Mesa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editar_mesa1" action="" method="POST" >
            <div class="form-group col-md-6">
            <label class="col-form-label">Area Mesa:</label>
            <select class="form-control input-sm id_area_fk" name="id_area_fk" required>
              <option > Seleccione una opcion</option>
              <?php
              $sql_vendedor=mysqli_query($sqlconnection,"select * from area_mesa order by id_area desc");
              while ($rw=mysqli_fetch_array($sql_vendedor)){
                $id_area=$rw["id_area"];
                $nombre_area=$rw["nombre_area"];


                ?>

                <option value="<?php echo $id_area?>" name="id_area_fk" class="id_area">
                  <?php echo $nombre_area ?>
                </option>
                <?php
              }
              ?>
            </select>
          </div>
            <div class="form-group col-md-6">
            <label class="col-form-label">Tipo de Mesa:</label>
            <select class="form-control input-sm id_tipo_fk" name="id_tipo_fk" required>
              <option > Seleccione una opcion</option>
              <?php
              $sql_vendedor=mysqli_query($sqlconnection,"select * from tipo_mesa order by id_tipo desc");
              while ($rw=mysqli_fetch_array($sql_vendedor)){
                $id_tipo=$rw["id_tipo"];
                $nombre_tipo=$rw["nombre_tipo"];


                ?>

                <option value="<?php echo $id_tipo?>" name="id_tipo_fk" class="id_tipo">
                  <?php echo $nombre_tipo ?>
                </option>
                <?php
              }
              ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="col-form-label">Numero:</label>
            <input type="text" required="required" id="numero_mesa" class="form-control" name="numero_mesa" placeholder="Numero Mesa" >
          </div>
        
          <div class="col-md-6 form-group">
            <label class="col-form-label">Estado :</label>
            <select class="form-control" name="estado_mesa" id="">
             <option value="Habilitada">Habilitada</option>
             <option value="Deshabilitada">Deshabilitada</option>
           </select>
         </div>
         <div class=" col-md-6">
           <input type="hidden" required="required" id="id_mesa" class="form-control" name="id_mesa" >
         </div>
       </form>
     </div>
     <div class="modal-footer">
      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
      <button type="submit" form="editar_mesa1" class="btn btn-success" name="editMesa">Editar</button>
    </div>
  </div>
</div>
</div>
<!-- Delete Modal-->
<div class="modal fade" id="eliminarMesa" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Estás seguro de eliminar este menú?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Seleccione "Eliminar" a continuación se eliminará <strong>todos</strong> su artículo o menú en esta categoría.</div>
      <div class="modal-footer">
        <form id="deletemenuform" method="POST">
          <input type="hidden" name="id_mesa" id="id_mesa">
        </form>
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
        <button type="submit" form="deletemenuform" class="btn btn-danger" name="eliminarMesa">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<?php include ('includes/adminfooter.php');?>
<script>
  $('#editItemModalMesa').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget); // Button that triggered the modal

          var id_mesa = button.data('id_mesa'); // Extract info from data-* attributes
          var tipo_mesa = button.data('tipo_mesa');
          var numero_mesa = button.data('numero_mesa');
          var estado_mesa = button.data('estado_mesa');
          

          // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
          var modal = $(this);
          modal.find('.modal-body #id_mesa').val(id_mesa);
          modal.find('.modal-body #tipo_mesa').val(tipo_mesa);
          modal.find('.modal-body #numero_mesa').val(numero_mesa);
          modal.find('.modal-body #estado_mesa').val(estado_mesa);

        });

  $('#eliminarMesa').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id_mesa = button.data('id_mesa'); // Extract info from data-* attributes
        

        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-body').html('Selecciona "Eliminar" y a continuación se borrará ');
        modal.find('.modal-footer #id_mesa').val(id_mesa);
      });
    </script>

