  <?php
  include("../functions.php");
  include('includes/adminheader.php');
  include ('includes/adminnav.php');
  include("agregar_gasto.php");
  include("editar_gasto.php");
  include("eliminar_gasto.php");


  ?>



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Administrar Gastos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Gastos</li>
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
              <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                  <button type="button" class="btn btn-primary btn-icon-split btn-block" data-toggle="modal" data-target="#agregarGasto">
                    <span class="icon text-white-50">
                      <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Agregar Gasto      </span>
                  </button>
                </h5>
              </div>
              <br>

              <div class="col-lg-12">
                <div class="card mb-3">
                  <div class="card-header bg-navy">
                    <i class="fas fa-fw fa-credit-card"></i>
                  Lista Actual de Gastos</div>
                  <div class="card-body caja">
                    <table class="display table table-bordered text-center " width="100%" cellspacing="0">
                      <thead>
                        <th>Gasto # </th>
                        <th>Fecha </th>
                        <th>Descripcion </th>
                        <th>Valor</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                      </thead>
                      <tbody id="caja_gastos">
                        <?php 
                        $displayStaffQuery = "SELECT *
                        FROM gastos
                        ORDER BY fecha_gasto DESC";

                        if ($result = $sqlconnection->query($displayStaffQuery)) {

                          if ($result->num_rows == 0) {

                          }
                          while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
                            $id_gasto=$fila['id_gasto'];
                            ?>  
                            <tr> 
                              <td><?php echo $fila['id_gasto']; ?></td>
                              <td><?php echo $fila['fecha_gasto']; ?></td>
                              <td><?php echo $fila['descripcion_gasto']; ?></td>
                              <td><?php echo $fila['valor_gasto']; ?></td>
                              <td>
                                <a class="btn  btn-warning" href="#editItemModalMesa" data-toggle="modal" data-fecha_gasto="<?php echo $fila["fecha_gasto"] ?>" data-descripcion_gasto="<?php echo $fila["descripcion_gasto"] ?>" data-valor_gasto="<?php echo $fila["valor_gasto"] ?>"  data-id_gasto="<?php echo $fila["id_gasto"] ?>" ><i class="fas fa-edit"></i></a>
                              </td>
                              <td>
                                <form action="" method="POST">
                                  <input type="hidden" value="<?php echo $id_gasto ?>" name="id_gasto">
                                  <button class="btn btn-danger" type="submit" name="eliminarG"><i class="fas fa-trash"></i></button>
                                </form>

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
              </div>
            </div>
          </div>

          <!-- Modal AGREGAR CLIENTE-->
          <div class="modal fade" id="agregarGasto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel">Agregar Gasto</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                  <form id="agregar_gasto" action="" method="POST">
                    <div class="form-group ">
                      <label class="col-form-label">Fecha Gasto:</label>
                      <input type="date" required="required" id="fecha_gasto" class="form-control" name="fecha_gasto"  >
                    </div>
                    <div class="form-group ">
                      <label class="col-form-label">Descripcion:</label>
                      <textarea name="descripcion_gasto" id="descripcion_gasto" cols="63" rows="4" class="form-control"></textarea>
                    </div>

                    <div class=" form-group">
                      <label class="col-form-label">Valor :</label>
                      <input type="number" required="required" id="valor_gasto" class="form-control" name="valor_gasto" placeholder="$0" >
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                 <button type="submit" form="agregar_gasto" class="btn btn-success" name="agregar_gasto">Enviar</button>
               </div>
             </div>
           </div>
         </div>


         <div class="modal fade" id="editItemModalMesa" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Editar Gasto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="editar_gasto" action="" method="POST" class="">

                  <div class="form-group ">
                    <label class="col-form-label">Fecha Gasto:</label>
                    <input type="date" required="required" id="fecha_gasto" class="form-control" name="fecha_gasto"  >
                  </div>
                  <div class="form-group ">
                    <label class="col-form-label">Descripcion:</label>
                    <textarea name="descripcion_gasto" id="descripcion_gasto" cols="63" rows="4" class="form-control"></textarea>
                  </div>

                  <div class=" form-group">
                    <label class="col-form-label">Valor :</label>
                    <input type="number" required="required" id="valor_gasto" class="form-control" name="valor_gasto" placeholder="$0" >
                    <input type="hidden" required="required" id="id_gasto" class="form-control" name="id_gasto" >
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                  <button type="submit" form="editar_gasto" class="btn btn-success" name="editar_gasto">Editar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Delete Modal-->

          <?php include ('includes/adminfooter.php');?>
          <script>
            $('#editItemModalMesa').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget); // Button that triggered the modal

          var id_gasto = button.data('id_gasto'); // Extract info from data-* attributes
          var fecha_gasto = button.data('fecha_gasto');
          var descripcion_gasto = button.data('descripcion_gasto');
          var valor_gasto = button.data('valor_gasto');
          

          // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
          var modal = $(this);
          modal.find('.modal-body #id_gasto').val(id_gasto);
          modal.find('.modal-body #fecha_gasto').val(fecha_gasto);
          modal.find('.modal-body #descripcion_gasto').val(descripcion_gasto);
          modal.find('.modal-body #valor_gasto').val(valor_gasto);

        });


      </script>


