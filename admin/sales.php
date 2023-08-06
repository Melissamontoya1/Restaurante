<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');

if (isset($_POST['anularf'])) {
  $orderID = $_POST['orderID'];
  $estado = $_POST['estado'];
  $motivo_anulacion = $_POST['motivo_anulacion'];
  $total=0;
  $subtotal=0;
  $editOrderQuery = "UPDATE tbl_order SET total = '{$total}',subtotal = '{$total}',status = '{$estado}' , motivo_anulacion = '{$motivo_anulacion}' WHERE orderID = '{$orderID}'";

  if ($sqlconnection->query($editOrderQuery) === TRUE) {
    echo "inserted.";
  } 

  else {
        //handle
    echo "someting wong";
    echo $sqlconnection->error;

  }
}

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Administrar Ventas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Ventas</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="accordion" id="accordionExample">


        <div class="card">
          <div class="card-header" id="headingThree">
            <h2 class="mb-0">
              <button class="btn btn-success btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <i class="fas fa-2x fa-file-excel pl-2"></i> 
                Exportar Informe 
              </button>
            </h2>
          </div>
          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body">
              <!-- Page Content -->


              <?php 
              $menu =  "SELECT * FROM tbl_menu ORDER BY menuID DESC";

              if ($orderResult = $sqlconnection->query($menu)) {

               if ($orderResult->num_rows == 0) {

                echo ' 

                <form method="post" class="row" action="reporte.php">
                <input type="date" name="fecha1" class"form-control">
                <input type="date" name="fecha2" class"form-control">
                <input type="submit" name="generar_reporte" class="btn btn-info">
                </form>
                </center>';
              }

              else {
                echo '<center> 
                <form class="form-inline" method="post" action="reporte.php">
                <div class="col-md-4">
                <label for="exampleInputName2">Fecha Inicio&nbsp;</label>
                <input type="date" name="fecha1" class="form-control">
                </div>
                <div class="col-md-4">
                <label for="exampleInputEmail2">&nbsp;Fecha Fin&nbsp;</label>
                <input type="date" name="fecha2" class="form-control">
                </div>          
                <div class="col-md-4">
                <label for="exampleInputEmail2">&nbsp;Categoria&nbsp;</label>


                <select name="menu" id="menu" class="form-control">
                <option value="todo">TODO</option>
                ';

                while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
                  $nombremenu=$orderRow['menuName'];
                  $codigo=$orderRow['menuID'];
                  echo '

                  <option value="'.$nombremenu.'">'.$nombremenu.'</option>';
                }

                echo'

                </select>
                </div>';

                $menuitem =  "SELECT * FROM tbl_menuitem ORDER BY itemID DESC";

                if ($orderResult = $sqlconnection->query($menuitem)) {

                 if ($orderResult->num_rows == 0) {

                  echo ' 
                  No hay Productos';
                }

                else {
                  echo '
                  <div class="col-md-4">
                  <label for="exampleInputEmail2">&nbsp;Producto&nbsp;</label>
                  <select name="item" id="item" class="form-control" >
                  <option class="form-control" value="todoitem">TODO</option>
                  ';

                  while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
                    $nombreitem=$orderRow['menuItemName'];
                    $codigoitem=$orderRow['itemID'];
                    echo '

                    <option value="'.$nombremenu.'">'.$nombreitem.'</option>';
                  }

                } 
              }
              echo'
              </select>
              </div>

              <div class="col-md-8">
              <label for="exampleInputEmail2">Estado</label>
              <select name="estado" id="estado" class="form-control">
              <option value="Activo">Activo</option>  
              <option value="Inactivo">Inactivo</option> 
              </select>
              </div>';
            } 
          }

          echo'

          <div class="col-md-12">
          <label for="exampleInputEmail2">&nbsp;</label>
          <input type="submit" name="generar_reporte" class="btn btn-info btn-block">
          </div>

          </form></center>';
          ?>


        </div>
      </div>
    </div>
  </div>




  <hr>
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-chart-area"></i>
      Estadística de Ganancias de Ventas
    </div>
    <div class="card-body">
      <table class="table table-bordered" width="100%" cellspacing="0">
        <tbody>
          <tr class="btn-warning">
            <td >Hoy</td>
            <td><B>COP $ <?php echo getSalesGrandTotal("DAY"); ?></B></td>
          </tr>
          <?php  if ($_SESSION['user_level'] == "admin" ) {?>
            <tr>
              <td>Esta Semana</td>
              <td><B>COP $ <?php echo getSalesGrandTotal("WEEK"); ?></B></td>
            </tr>
            <tr>
              <td>Este Mes</td>
              <td><B>COP $ <?php echo getSalesGrandTotal("MONTH"); ?></B></td>
            </tr>
            <tr class="btn-primary ">
              <td><b>Todo el Tiempo</b></td>
              <td><B>COP $ <?php echo getSalesGrandTotal("ALLTIME"); ?></B></td>
            </tr> 
          <?php  }?>
        </tbody>
      </table>
    </div>

    <!-- BUSCAR FACTURAS -->

    <br>

    <!-- TABLA DE CONSULTAS DE FACTURAS -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Órdenes de Ventas</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <!-- BUSCAR FACTURAS -->
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
              <center>
                <input type="text" name="buscar_fact" placeholder="Buscar Factura" class="form-control" id="buscar_fact">  
              </center>
            </div>
            <div class="col-md-2"></div>

          </div>
          <br>
          <table id="ventas" class="table table-bordered" width="100%" cellspacing="0">
              <thead class="bg-green">
                  <tr class="text-center">
                    <th># / Mesa / Cliente </th>
                    <th>Categoría</th>
                    <th>Nombre de Producto</th>
                    <th>Cant</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Total (COP)</th>
                    <th>Fecha</th>
                    <th>Btn</th>
                  </tr>
                </thead>
            <tbody id="tblBodyCurrentOrder">
              <?php 
              $displayOrderQuery =  "
              SELECT o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo,o.nombre_opcional
                  FROM tbl_order o
                  INNER JOIN tbl_orderdetail od
                  ON o.orderID = od.orderID
                  INNER JOIN tbl_menuitem mi
                  ON od.itemID = mi.itemID
                  INNER JOIN tbl_menu m
                  ON mi.menuID = m.menuID
                  INNER JOIN cliente c
                  ON o.id_cliente_fk = c.id_cliente
                  INNER JOIN mesa me
                  ON o.id_mesa_fk = me.id_mesa
                  INNER JOIN area_mesa a
                  ON me.id_area_fk=a.id_area
                  INNER JOIN tipo_mesa t 
                  ON me.id_tipo_fk= t.id_tipo
              ORDER BY o.orderID DESC
              ";

              if ($orderResult = $sqlconnection->query($displayOrderQuery)) {

                $currentspan = 0;
                $total = 0;
                if ($orderResult->num_rows > 0) {
                  while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
                    $estado=$orderRow['status'];
                    $motivo_anulacion=$orderRow['motivo_anulacion'];
                    

                            //basically count rowspan so no repetitive display id in each table row
                    $rowspan = getCountID($orderRow["orderID"],"orderID","tbl_orderdetail"); 

                    if ($currentspan == 0) {
                      $currentspan = $rowspan;
                      $total = 0;
                    }

                            //get total for each order id
                    $total += ($orderRow['price']*$orderRow['quantity']);


                   
                        echo "<tr class='text-center ' >";

                        if ($currentspan == $rowspan) {
                          echo "<td rowspan=".$rowspan. " class='bg-navy'>
                          <B> #".$orderRow['prefijo']."-".$orderRow['orderID']."</B> <hr>
                          <B>Area :".$orderRow['nombre_area']."</B><hr>
                          <B>".$orderRow['nombre_tipo']." # ".$orderRow['numero_mesa']."</B><hr>

                          <B>Cliente : ".$orderRow['nombres']."</B>
                          <hr>
                              <B>Apodo : ".$orderRow['nombre_opcional']."</B>
                          </td>";
                        }

                    
                    echo "
                    <td>".$orderRow['menuName']."</td>
                    <td>".$orderRow['menuItemName']."</td>
                    <td class='text-center'>".$orderRow['quantity']."</td>
                    <td class='text-center'>$".number_format($orderRow['precio'])."</td>
                    <td class='text-center'>$".number_format($orderRow['totalv'])."</td>

                    ";

                    if ($currentspan == $rowspan) {

                      $color = "badge";

                      switch ($orderRow['status']) {
                        case 'Esperando':
                        $color = "badge badge-warning";
                        break;

                        case 'En blanco':
                        $color = "badge badge-primary";
                        break;

                        case 'Pendiente de Pago':
                        $color = "badge badge-danger";
                        break;

                        case 'Factura Anulada':
                        $color = "badge badge-danger";
                        break;

                        case 'Vendido':
                        $color = "badge badge-success";
                        break;
                        case 'Anulada':
                        $color = "badge badge-secondary";
                        break;
                        case 'Credito':
                        $color = "badge bg-purple";
                        break;
                      }

                      echo "<td class='text-center' rowspan=".$rowspan."><span class='{$color}'>".$orderRow['status']."</span></td>";

                      echo "<td rowspan=".$rowspan." class='text-center'>$".number_format(getSalesTotal($orderRow['orderID']))."</td>";

                      echo "<td rowspan=".$rowspan." class='text-center'>".$orderRow['order_date']."</td>";
                      ?>
                      <td class='text-center' <?php echo 'rowspan='.$rowspan?> >

                        <?php 
                        if ($estado=="Pendiente de Pago") {?>
                          <a href="editfact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-block btn-warning"><i class="fas fa-concierge-bell"></i></a>
                          
                          <a href="consultar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-block btn-info"><i class="fas fa-search"></i></a>
                          <a href="pagar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-block btn-success"><i class="fas fa-cash"></i>Pagar</a>

                          <a href="eliminar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-block btn-danger"><i class="fas fa-trash"></i></a>
                          <?php  
                        }else{?>
                         <?php if ($motivo_anulacion==""){
                          echo $motivo_anulacion;
                          ?>

                          <button type="button" class="btn btn-secondary btn-block" data-toggle="modal" data-target="#modal-secondary" data-id="<?php echo $orderRow["orderID"] ?>">
                            Anular 

                          </button> 
                        <?php }else{ ?>
                         <button type="button" class="btn bg-navy btn-block" data-toggle="modal" data-target="#modal-anulacion" data-motivo="<?php echo $orderRow["motivo_anulacion"]  ?> " data-id="<?php echo $orderRow["orderID"] ?>">
                           Motivo Anulacion
                         </button> 
                       <?php } 

                      ?>
                       <a href="editfact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-warning btn-block"><i class="fa fa-edit"></i></a>

                       <a href="consultar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-info btn-block"><i class="fas fa-search"></i></a>

                       <a href="eliminar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn  btn-danger btn-block"><i class="fas fa-trash"></i></a>


                       <?php  
                     }

                     ?>
                   </td>


                   <?php  
                 }

                 echo "</tr>";

                 $currentspan--;
               }
             }
           }
           ?>
         </tbody>
       </table>

     </div>
   </div>
 </div>

 <!-- /.container-fluid -->

 <!-- /.container-fluid -->


</div>
</div>
</section>
</div>
<div class="modal fade" id="modal-secondary">
  <div class="modal-dialog">
    <div class="modal-content bg-secondary">
      <div class="modal-header">
        <h4 class="modal-title">¿Estas seguro de realizar esta anulación?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" id="anulafact">
          <div class="modalsito"> 
            <input type="hidden" id="numero_orden" name="orderID">
            <textarea name="motivo_anulacion"  cols="30" rows="10" class="form-control" cols="30" rows="10" placeholder="Describa porque se hara  la anulacion de esta Factura" required></textarea>
            <input type="hidden" name="estado" value="Anulada" >
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" name="anularf" form="anulafact">Anular Factura</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="modal fade" id="modal-anulacion">
  <div class="modal-dialog">
    <div class="modal-content bg-secondary">
      <div class="modal-header">
        <h4 class="modal-title">Motivo por el cual se anulo la</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" id="anulafact">
          <div class="modalsito"> 
            <label for="">Factura Número</label>
            <input type="number" id="numero_orden" name="orderID" readonly>
            <textarea name="motivo_anulacion"  cols="30" rows="10" class="form-control" cols="30" rows="10" placeholder="Describa porque se hara  la anulacion de esta Factura" required id="motivo_anulacion" readonly> </textarea>

          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Cerrar</button>
      </button>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php include ('includes/adminfooter.php');?>
<script>

            //passing menuId to modal
            $('#modal-secondary').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var orden = button.data('id'); // Extract info from data-* attributes


        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modalsito #numero_orden').val(orden);

      });
                        //passing menuId to modal
                        $('#modal-anulacion').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var anulacionm = button.data('motivo'); // Extract info from data-* attributes
        var orden = button.data('id'); // Extract info from data-* attributes


        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modalsito #motivo_anulacion').val(anulacionm);
        modal.find('.modalsito #numero_orden').val(orden);

      });
    </script>