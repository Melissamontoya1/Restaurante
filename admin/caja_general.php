  <?php
  include("../functions.php");
  include('includes/adminheader.php');
  include ('includes/adminnav.php');
  include('editar_caja.php');
  include('ingreso.php');
  ?>



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Información Caja General</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Caja General</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

     <div class="row">
      <div class="col-md-12">
       <center>
        <!-- Agregar Cliente modal -->
        <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#agregarGasto">
         <span class="icon text-white-50">
          <i class="fas fa-plus"></i>
        </span>
        <span class="text"> Nuevo Ingreso </span>
      </button>
    </center>
  </div>

  <br>
  <br>
  <!-- SUMAS DE TOTALES -->
  <?php 	
  $sumageneral =  "
  SELECT SUM(valor_ingreso) AS totali
  FROM ingresos_caja

  WHERE estado_ingreso='general'";

  if ($orderResult = $sqlconnection->query($sumageneral)) {

   if ($orderResult->num_rows == 0) {

    echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
  }

  else {
    while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {

     $total_ingresos=$fila['totali']; 


   }
 }     
}
$valor_caja =  "
SELECT SUM(valor_ingreso) AS totalv
FROM ingresos_caja

WHERE estado_ingreso='venta'";

if ($orderResult = $sqlconnection->query($valor_caja)) {

 if ($orderResult->num_rows == 0) {

  echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
}

else {
  while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {

   $total_ventas=$fila['totalv']; 


 }
}     
}
$sumagastos =  "
SELECT SUM(valor_gasto) AS totalg
FROM gastos
";

if ($orderResult = $sqlconnection->query($sumagastos)) {

 if ($orderResult->num_rows == 0) {

  echo "<tr><td class='text-center' colspan='7' >Actualmente no hay pedido en este momento. </td></tr>";
}

else {
  while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {

   $total_gastos=$fila['totalg']; 


 }
}     
}
?>
<div class="col-lg-12">
 <div class="card mb-3">
  <div class="card-header bg-navy">
   <i class="fas fa-chart-area"></i>
   Caja General
 </div>
 <div class="card-body col-lg-12">
   <table class="table table-bordered" width="100%" cellspacing="0">
    <tbody>
     <tr class="btn-warning">
      <td >Ingresos</td>
      <td><B>COP $ <?php echo number_format($total_ingresos); ?></B></td>
    </tr>

    <tr>
      <td>Ventas</td>
      <td><B>COP $ <?php echo number_format($total_ventas); ?></B></td>
    </tr>
    <tr>
      <td>Gastos</td>
      <td><B>COP $ <?php echo number_format($total_gastos); ?></B></td>
    </tr>
    <?php 	$estado_ingreso=($total_ingresos+$total_ventas)-$total_gastos; ?>
    <tr class="btn-primary ">
      <td><b>Estado Actual</b></td>
      <td><B>COP $ <?php echo number_format($estado_ingreso); ?></B></td>
    </tr> 

  </tbody>
</table>
</div>

</div>
<div class="col-lg-12">
  <div class="card mb-3">
   <div class="card-header">
    <i class="fas fa-fw fa-credit-card"></i>
    Lista actual de <B>Ingresos a la Caja General</B></div>
    <div class="card-body caja">
     <table class="display table table-bordered text-center "  width="100%" cellspacing="0">
      <thead>
       <th>Ingreso # </th>
       <th>Fecha </th>
       <th>Descripcion Ingreso</th>
       <th>Valor Ingreso</th>
       <th>Editar</th>
       <th>Eliminar</th>
     </thead>
     <tbody id="caja_gastos">
       <?php 
       $displayStaffQuery = "SELECT *
       FROM ingresos_caja
       ORDER BY id_ingresos ASC";

       if ($result = $sqlconnection->query($displayStaffQuery)) {

        if ($result->num_rows > 0) {

         while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
          $id_ingresos=$fila['id_ingresos'];
          $fecha_ingreso=$fila['fecha_ingreso'];
          $descripcion_ingreso=$fila['descripcion_ingreso'];
          $estado_ingreso=$fila['estado_ingreso'];
          $valor_ingreso=$fila['valor_ingreso'];
          ?>  
          <tr> 

           <?php if ($estado_ingreso=="factura"){?>
            <td><?php echo $id_ingresos ?></td>
            <td><?php echo $fecha_ingreso ?></td>
            <td><?php echo $descripcion_ingreso ?></td>
            <td><?php echo $valor_ingreso ?></td>
            <td>No se puede editar</td>
            <td>No se puede eliminar</td>
            <?php  
          }?>
          <td><?php echo $id_ingresos ?></td>
          <td><?php echo $fecha_ingreso ?></td>
          <td><?php echo $descripcion_ingreso ?></td>
          <td><?php echo $valor_ingreso ?></td>
          <td>
            <a class="btn btn-sm btn-warning" href="#editItemModalMesa" data-toggle="modal" data-nombre_caja="<?php echo $fila["nombre_caja"] ?>" data-estado_caja="<?php echo $fila["estado_caja"] ?>"   data-id_caja="<?php echo $fila["id_caja"] ?>" ><i class="fas fa-edit"></i></a>

          </td>
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
    <h4 class="modal-title" id="myModalLabel">Agregar Nuevo Ingreso</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">
    <form id="agregar_cuenta" action="" method="POST">
     <div class="form-group ">
      <label class="col-form-label">Fecha Ingreso</label>
      <input type="date" required="required" id="fecha_ingreso" class="form-control" name="fecha_ingreso"  >
    </div>
    <div class="form-group ">
      <label class="col-form-label">Descripción Ingreso</label>
      <input type="text" required="required" id="descripcion_ingreso" class="form-control" name="descripcion_ingreso" placeholder="Ingreso" >
    </div>
    <div class="form-group ">
      <label class="col-form-label">Valor Ingreso</label>
      <input type="text" required="required" id="valor_ingreso" class="form-control" name="valor_ingreso"  placeholder="$0">
    </div>

  </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
  <button type="submit" form="agregar_cuenta" class="btn btn-success" name="ingreso">Enviar</button>
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
  					    if (window.history.replaceState) { // verificamos disponibilidad
  					    	window.history.replaceState(null, null, window.location.href);
  					    }
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

