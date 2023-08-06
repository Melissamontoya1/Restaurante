<?php 
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
include("base_dia.php");

function getStatus () {
  global $sqlconnection;
  $checkOnlineQuery = "SELECT status FROM tbl_staff WHERE staffID = {$_SESSION['uid']}";

  if ($result = $sqlconnection->query($checkOnlineQuery)) {

    if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
      return $row['status'];
    }
  }

  else {
    echo "Something wrong the query!";
    echo $sqlconnection->error;
  }
}
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
WHERE o.status='Pendiente de Pago' OR o.status='Entregado' ORDER BY o.orderID DESC
";
$displayStaffQuery = "SELECT m.id_mesa,m.numero_mesa,m.estado_mesa,m.id_area_fk,m.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
FROM mesa m
INNER JOIN area_mesa a
ON m.id_area_fk=a.id_area
INNER JOIN tipo_mesa t 
ON m.id_tipo_fk= t.id_tipo GROUP BY a.nombre_area ";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Content Header (Page header) -->


  <!-- Main content -->
  <section class="content">

    <div class="container-fluid">

      <div class="card shadow mb-4">
        <?php if ($tipo_negocio=="Carniceria") {?>
          <div class="aviso"></div>
          <?php  
        } ?>
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Lista de Órdenes de Ventas </h6>
        </div>
        <div class="card-body">
         <div class="col-md-12">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#mapa" data-toggle="tab">Mapa</a></li>
                <li class="nav-item"><a class="nav-link" href="#listado" data-toggle="tab">Listado</a></li>
                <li class="nav-item"><a class="nav-link" href="#divididas" data-toggle="tab">Mesas Divididas</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="mapa">

                  <div class="card-header p-2">
                    <ul class="nav nav-pills">
                      <?php 


                      if ($result = $sqlconnection->query($displayStaffQuery)) {

                        if ($result->num_rows == 0) {
                          echo "<h2>No hay mesas Disponibles.</h2>";
                        }
                               //CONTADOR PARA QUE EL PRIMER SLIDER SEA EL ACTIVO
                        $i=1;
                        while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
                          $id_mesa=$fila['id_mesa'];
                          $nombre_area=$fila['nombre_area'];

                          if($nombre_area=="Mesa Dividida"){

                          }else{
                            ?> 
                            <li class="nav-item"><a class="nav-link " href="#m<?php echo $nombre_area  ?>" data-toggle="tab"><?php echo $nombre_area ?></a></li>



                            <?php $i++; } } } ?>
                          </ul>
                        </div>

                        <div class="card-body ">
                          <div class="tab-content ">
                            <?php 

                            if ($result33 = $sqlconnection->query($displayStaffQuery)) {

                              if ($result33->num_rows == 0) {
                                echo "<h2>No hay mesas Disponibles.</h2>";
                              }
                               //CONTADOR PARA QUE EL PRIMER SLIDER SEA EL ACTIVO
                              $i=0;
                              while($filam = $result33->fetch_array(MYSQLI_ASSOC)) {
                                $id_mesa=$filam['id_mesa'];
                                $nombre_aream=$filam['nombre_area'];
                                ?>

                                <div class="col-md-12 <?php if($i==1) echo "active"; ?> tab-pane" id="m<?php echo $nombre_aream  ?>">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="row">
                                        <?php 
                                        $displayStaffQuery = "SELECT m.id_mesa,m.numero_mesa,m.estado_mesa,m.id_area_fk,m.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
                                        FROM mesa m
                                        INNER JOIN area_mesa a
                                        ON m.id_area_fk=a.id_area
                                        INNER JOIN tipo_mesa t 
                                        ON m.id_tipo_fk= t.id_tipo 

                                        WHERE a.nombre_area='{$nombre_aream}' AND m.estado_mesa='Habilitada'";

                                        if ($result = $sqlconnection->query($displayStaffQuery)) {

                                          if ($result->num_rows == 0) {
                                            echo "<td colspan='4'>No hay mesas Disponibles.</td>";
                                          }
                                          while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
                                            $id_mesa=$fila['id_mesa'];
                                            $estado_mesa=$fila['estado_mesa'];
                                            $numero_mesa=$fila['numero_mesa'];
                                            $nombre_area=$fila['nombre_area'];
                                            $nombre_tipo=$fila['nombre_tipo'];
                                            if($nombre_area=="Mesa Dividida"){

                                            }else{
                                              ?>
                                              <div class="col-md-6 col-sm-6 col-12 card-body">
                                                <div class="info-box bg-success">
                                                  <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>

                                                  <div class="info-box-content text-center">
                                                    <span class="info-box-text"><?php echo $estado_mesa; ?></span>
                                                    <span class="info-box-number"><?php echo $nombre_area ?></span>
                                                    <span class="progress-description">
                                                     <?php echo $nombre_tipo ." / # ". $numero_mesa ?>
                                                   </span>

                                                 </div>

                                                 <!-- /.info-box-content -->
                                               </div>
                                               <div class="col-md-12 card">
                                                <!-- <button class="btn btn-warning btn-block" title="Reservar mesa">Reservar</button type="hidden"> -->
                                                <a href="order.php?id_mesa=<?php echo $fila['id_mesa']; ?>"><button class="btn btn-primary btn-block" title="Tomar orden">Tomar Orden</button></a>

                                              </div>
                                              <!-- /.info-box -->
                                            </div>
                                            <?php  

                                          }
                                        }
                                      }else {
                                        echo $sqlconnection->error;
                                        echo "ERROR.";
                                      } ?>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="row">
                                      <?php 
                                      $displayOrderQuery2 =  "
                                      SELECT o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo,o.total,o.nombre_opcional
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
                                      WHERE a.nombre_area='{$nombre_aream}' AND o.status='Pendiente de Pago' OR o.status='Entregado' GROUP BY o.orderID 
                                      ";

                                      if ($orderResult2 = $sqlconnection->query($displayOrderQuery2)) {



                        //if no order
                                        if ($orderResult2->num_rows == 0) {


                                        }

                                        else {
                                          while($orderRow2 = $orderResult2->fetch_array(MYSQLI_ASSOC)) {
                                            $id_mesa=$orderRow2['id_mesa'];
                                            $estado_mesa=$orderRow2['estado_mesa'];
                                            $numero_mesa=$orderRow2['numero_mesa'];
                                            $nombre_area=$orderRow2['nombre_area'];
                                            $nombre_tipo=$orderRow2['nombre_tipo'];

                                            updateTotal($orderRow2['orderID']);
                                            ?>


                                            <div class="card card-widget widget-user col-md-6">
                                              <!-- Add the bg color to the header using any of the bg-* classes -->
                                              <div class="widget-user-header bg-danger">
                                                <h3 class="widget-user-username"><?php echo $orderRow2['nombres'];?></h3>
                                                <?php echo $orderRow2['nombre_opcional'] ?>
                                                <h5 class="widget-user-desc"><?php echo $orderRow2["nombre_tipo"]." # ".$orderRow2["numero_mesa"]; ?></h5>
                                                <h2 class="widget-user-desc">$ <?php echo number_format($orderRow2["total"]); ?></h2>
                                              </div>

                                              <div class="card-footer">
                                                <div class="row">
                                                  <div class="col-sm-4 border-right">
                                                    <div class="description-block">
                                                      <a href="editfact.php?orderID=<?php echo $orderRow2['orderID']; ?>" class="btn  btn-warning  btn-block"><i class="fas fa-concierge-bell "></i></a>
                                                    </div>
                                                    <!-- /.description-block -->
                                                  </div>
                                                  <!-- /.col -->
                                                  <div class="col-sm-4 border-right">
                                                    <div class="description-block">
                                                      <a href="consultar_fact.php?orderID=<?php echo $orderRow2['orderID'];  ?>" class="btn  btn-info btn-block"><i class="fas fa-search"></i></a>
                                                    </div>
                                                    <!-- /.description-block -->
                                                  </div>
                                                  <!-- /.col -->
                                                  <div class="col-sm-4">
                                                    <div class="description-block">
                                                      <a href="pagar_fact.php?orderID=<?php echo $orderRow2['orderID']; ?>" class="btn  btn-success btn-block"><i class="fas fa-dollar-sign "></i></a>
                                                    </div>
                                                    <!-- /.description-block -->
                                                  </div>
                                                  <!-- /.col -->
                                                </div>
                                                <!-- /.row -->
                                              </div>
                                            </div>
                                            <!-- /.widget-user -->




                                            <?php  

                                          }
                                        }
                                      }

                                      ?>
                                    </div>
                                  </div>
                                </div>

                              </div>
                              <?php $i++;  


                            } } 
                            ?>


                          </div>

                        </div>

                      </div><!-- /.CIERRA MAPA -->

                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="listado">
                       <table  id="ventas" class=" table table-bordered table-responsive" width="100%" cellspacing="0">
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
                       <tbody>
                        <?php 

                        if ($orderResult = $sqlconnection->query($displayOrderQuery)) {

                          $currentspan = 0;
                          $total = 0;

                        //if no order
                          if ($orderResult->num_rows == 0) {


                          }

                          else {
                            while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
                              $estado=$orderRow['status'];


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
                              <td>".$orderRow['quantity']."</td>
                              <td>$".number_format($orderRow['precio'])."</td>
                              <td>$".number_format($orderRow['totalv'])."</td>

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
                                  case 'Entregado':
                                  $color = "badge badge-primary";
                                  break;
                                }

                                echo "<td class='text-center' rowspan=".$rowspan."><span class='{$color}'>".$orderRow['status']."</span></td>";


                                echo "<td rowspan=".$rowspan." class='text-center'><B>$".number_format(getSalesTotal($orderRow['orderID']))."</B></td>";

                                echo "<td rowspan=".$rowspan." class='text-center'>".$orderRow['order_date']."</td>";
                                ?>
                                <td class='text-center' <?php echo 'rowspan='.$rowspan?> >

                                  <?php 
                                  if ($estado=="Pendiente de Pago" || $estado=="Entregado") {?>
                                    <a href="editfact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn  btn-warning  btn-block"><i class="fas fa-concierge-bell "></i></a>

                                    <a href="consultar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn  btn-info btn-block"><i class="fas fa-search"></i></a>
                                    <a href="pagar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn  btn-success btn-block"><i class="fas fa-cash "></i>Pagar</a>
                                    <?php  
                                  }else{?>
                                    <a href="editfact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn  btn-warning"><i class="fas fa-edit btn-block"></i></a>

                                    <a href="consultar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn  btn-info btn-block"><i class="fas fa-search"></i></a>
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
                  <div class="tab-pane" id="divididas">

                    <div class="row">
                      <?php 
                      $displayOrderQuery2 =  "
                      SELECT o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo,o.total,o.nombre_opcional
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
                      WHERE a.nombre_area='Mesa Dividida' AND o.status='Pendiente de Pago' OR o.status='Entregado' GROUP BY o.orderID 
                      ";

                      if ($orderResult2 = $sqlconnection->query($displayOrderQuery2)) {

                        $currentspan2 = 0;
                        $total2 = 0;

                        //if no order
                        if ($orderResult2->num_rows == 0) {


                        }

                        else {
                          while($orderRow2 = $orderResult2->fetch_array(MYSQLI_ASSOC)) {
                            $id_mesa=$orderRow2['id_mesa'];
                            $estado_mesa=$orderRow2['estado_mesa'];
                            $numero_mesa=$orderRow2['numero_mesa'];
                            $nombre_area=$orderRow2['nombre_area'];
                            $nombre_tipo=$orderRow2['nombre_tipo'];
                            ?>


                            <div class="card card-widget widget-user col-md-3">
                              <!-- Add the bg color to the header using any of the bg-* classes -->
                              <div class="widget-user-header bg-danger ">
                                <h3 class="widget-user-username"><?php echo $orderRow2['nombres']; ?></h3>
                                <?php echo $orderRow2['nombre_opcional'] ?>
                                
                                <h5 class="widget-user-desc"><?php echo $orderRow2["nombre_tipo"]." # ".$orderRow2["numero_mesa"]; ?></h5>
                                <h2 class="widget-user-desc">$ <?php echo number_format($orderRow2["total"]); ?>  </h2>

                              </div>

                              <div class="card-footer">
                                <div class="row">
                                  <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                      <a href="editfact.php?orderID=<?php echo $orderRow2['orderID']; ?>" class="btn  btn-warning  btn-block"><i class="fas fa-concierge-bell "></i></a>
                                    </div>
                                    <!-- /.description-block -->
                                  </div>
                                  <!-- /.col -->
                                  <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                      <a href="consultar_fact.php?orderID=<?php echo $orderRow2['orderID'];  ?>" class="btn  btn-info btn-block"><i class="fas fa-search"></i></a>
                                    </div>
                                    <!-- /.description-block -->
                                  </div>
                                  <!-- /.col -->
                                  <div class="col-sm-4">
                                    <div class="description-block">
                                      <a href="pagar_fact.php?orderID=<?php echo $orderRow2['orderID']; ?>" class="btn  btn-success btn-block"><i class="fas fa-dollar-sign "></i></a>
                                    </div>
                                    <!-- /.description-block -->
                                  </div>
                                  <!-- /.col -->
                                </div>
                                <!-- /.row -->
                              </div>
                            </div>
                            <!-- /.widget-user -->




                            <?php  

                          }
                        }
                      }

                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.tab-content -->
            </div><!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
  



  <script type="text/javascript">

    $( document ).ready(function() {
      refreshTableOrder();
    });

    function refreshTableOrder() {
      $( "#orderTable" ).load( "displayorder.php?cmd=currentready" );
    }

    //refresh order current list every 3 secs
    setInterval(function(){ refreshTableOrder(); }, 3000);

  </script>

  <?php include ('includes/adminfooter.php');?>


</body>
</html>